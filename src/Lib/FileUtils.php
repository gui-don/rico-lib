<?php

declare(strict_types=1);

namespace Rico\Lib;

use Rico\Slib\FileUtils as StaticFileUtils;

class FileUtils
{
    const LIST_DIRECTORY_FILE_ONLY = 1;
    const LIST_DIRECTORY_DIR_ONLY = 2;
    const LIST_DIRECTORY_BOTH = 3;

    /**
     * Gets filenames and folders names (according to $option) inside a $path.
     *
     * @param string $path
     * @param int    $option
     *
     * @return string[]
     */
    public function listDirectory(string $path, int $option = self::LIST_DIRECTORY_BOTH): array
    {
        return StaticFileUtils::listDirectory($path, $option);
    }

    /**
     * Creates the completer $path with all missing intermediates directories.
     *
     * @param string $path
     *
     * @return bool
     */
    public function createPath(string $path): bool
    {
        return StaticFileUtils::createPath($path);
    }

    /**
     * Creates a symbolic $link pointing to $file.
     *
     * @param string $link
     * @param string $file
     *
     * @return bool
     */
    public function createSymlink(string $link, string $file): bool
    {
        return StaticFileUtils::createSymlink($link, $file);
    }

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
}
