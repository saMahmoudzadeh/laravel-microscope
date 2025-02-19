<?php

namespace Imanghafoori\LaravelMicroscope\SearchReplace;

use ErrorException;
use Illuminate\Console\Command;
use Imanghafoori\LaravelMicroscope\ErrorReporters\ErrorPrinter;
use Imanghafoori\LaravelMicroscope\ForPsr4LoadedClasses;
use Imanghafoori\SearchReplace\Filters;
use Imanghafoori\SearchReplace\PatternParser;

class CheckRefactorsCommand extends Command
{
    protected $signature = 'search_replace {--N|name=} {--t|tag=} {--f|file=} {--d|folder=} {--s|nofix}';

    protected $description = 'Does refactoring.';

    public function handle(ErrorPrinter $errorPrinter)
    {
        $this->info('Checking for refactors...');

        Filters::$filters['is_sub_class_of'] = IsSubClassOf::class;

        app()->singleton('current.command', function () {
            return $this;
        });

        $errorPrinter->printer = $this->output;

        try {
            $patterns = require base_path('/search_replace.php');
        } catch (ErrorException $e) {
            file_put_contents(base_path('/search_replace.php'), $this->stub());

            $this->getOutput()->writeln('The "search_replace.php" was created.');

            return;
        }

        $patterns = $this->filter($this->option('name'), $this->option('tag'), $patterns);

        if ($this->option('nofix')) {
            foreach ($patterns as &$pattern) {
                unset($pattern['replace']);
            }
        }

        if (! $patterns) {
            $this->getOutput()->writeln('No pattern found...');

            return;
        }

        $patterns = $this->normalizePatterns($patterns);
        $parsedPatterns = PatternParser::parsePatterns($patterns);

        $file = ltrim($this->option('file'), '=');
        $folder = ltrim($this->option('folder'), '=');

        ForPsr4LoadedClasses::checkNow([PatternRefactorings::class], [$parsedPatterns, $patterns], $file, $folder);

        $this->getOutput()->writeln(' - Finished search/replace');

        return PatternRefactorings::$patternFound ? 1 : 0;
    }

    private function stub()
    {
        return file_get_contents(__DIR__.'/search_replace.stub');
    }

    private function normalizePatterns($refactors)
    {
        foreach ($refactors as $i => $ref) {
            isset($ref['directory']) && $refactors[$i]['directory'] = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $ref['directory']);
        }

        return $refactors;
    }

    private function filter($name, $tag, $patterns)
    {
        if ($name && isset($patterns[$name])) {
            return [$name => $patterns[$name]];
        }

        if ($tag) {
            $filteredPatterns = [];
            foreach ($patterns as $name => $pattern) {
                if (isset($pattern['tags'])) {
                    $tags = $pattern['tags'];
                    is_string($tags) && $tags = explode(',', $tags);
                    if (in_array($tag, $tags)) {
                        $filteredPatterns[$name] = $pattern;
                    }
                }
            }

            return $filteredPatterns;
        }

        return $patterns;
    }
}
