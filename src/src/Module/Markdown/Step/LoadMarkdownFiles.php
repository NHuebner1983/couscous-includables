<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Step;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Loads Markdown files in memory.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadMarkdownFiles implements Step
{
    private $constants;

    public function __invoke(Project $project)
    {
        $files = $project->sourceFiles();
        $files->name('*.md');

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $content = $this->importIncludes(file_get_contents($file->getPathname()), $file->getPath());

            $project->addFile(new MarkdownFile($file->getRelativePathname(), $content));
        }

        $project->watchlist->watchFiles($files);
    }

    private function importIncludes($content, $base_path)
    {
        $open  = '[include(';
        $close = ')]';

        if ( ! strstr($content, $open) || ! strstr($content, $close) )
        {
            return $content;
        }

        while ( strstr($content, $open) && strstr($content, $close) )
        {
            $statement    = substr($content, strpos($content, $open));
            $statement    = trim(substr($statement, 0, strpos($statement, $close) + strlen($close)));
            $file_include = substr($statement, strpos($statement, '(') + 1);
            $file_include = substr($file_include, 0, strpos($file_include, $close));
            $file_include = $this->unwrap($file_include, ["'", '"']);

            if ( substr($file_include, 0, 1) != '/' )
            {
                $file_include = $base_path . '/' . $file_include;
            }

            $file_contents = "# File Not Found: {$file_include}";

            if ( is_file($file_include) )
            {
                $file_contents = file_get_contents($file_include);
            }

            $content = $this->importConstants(str_replace($statement, $file_contents, $content), $base_path);
        }

        return $content;
    }

    private function importConstants($content, $base_path)
    {
        $open    = '@';
        $connect = '=';

        $constants_file = "{$base_path}/constants.mdd";

        if ( file_exists($constants_file) )
        {
            $constants = explode(chr(10), file_get_contents($constants_file));

            foreach ( $constants as $constant )
            {
                $constant = trim($constant);

                if ( substr($constant, 0, 1) != $open || ! strstr($constant, $connect) )
                {
                    continue;
                }

                $constant = substr($constant, 1);
                $value    = trim(substr($constant, strpos($constant, $connect) + 1));
                $constant = trim(substr($constant, 0, strpos($constant, $connect)));

                $this->constants[$constant] = $value;
            }
        }

        if ( empty($this->constants) )
        {
            return $content;
        }

        foreach ( $this->constants as $constant => $value )
        {
            $constant_find = "[@{$constant}]";
            $content       = str_replace($constant_find, $value, $content);
        }

        return $content;
    }

    private function unwrap($str, $encapsulated = [])
    {
        $str          = trim($str);
        $encapsulated = ! is_array($encapsulated) ? [$encapsulated] : $encapsulated;

        foreach ( $encapsulated as $unwrap )
        {
            if ( substr($str, 0, 1) == $unwrap )
            {
                $str = substr($str, 1);
                $str = $this->unwrap($str, $encapsulated);
            }
            if ( substr($str, -1) == $unwrap )
            {
                $str = substr($str, 0, -1);
                $str = $this->unwrap($str, $encapsulated);
            }
        }

        return $str;
    }

}
