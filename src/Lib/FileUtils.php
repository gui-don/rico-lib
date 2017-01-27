<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\FileUtils as StaticFileUtils;

class FileUtils
{
    /**
     * Counts the number of lines in a $file.
     *
     * @param string $file
     * @param bool   $countEmpty
     *
     * @return int|bool
     */
    public function count(string $file, bool $countEmpty = false)
    {
        return StaticFileUtils::count($file, $countEmpty);
    }

    /**
     * Adds a new $line at the end of a $file without duplication.
     *
     * @param string $file
     * @param string $line
     *
     * @return bool|null
     */
    public function addLine(string $file, string $line)
    {
        return StaticFileUtils::addLine($file, $line);
    }

    /**
     * Extracts the extension (without the dot) of a filename alone or contained in a path.
     *
     * @param string $filename
     *
     * @return string
     */
    public function extractExtension(string $filename): string
    {
        return StaticFileUtils::extractExtension($filename);
    }
}
