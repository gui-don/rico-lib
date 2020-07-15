<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class FilesystemUtils
{
    const LIST_DIRECTORY_FILE_ONLY = 1;
    const LIST_DIRECTORY_DIR_ONLY = 2;
    const LIST_DIRECTORY_BOTH = 3;

    /**
     * Creates the completer $path with all missing intermediates directories.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function createPath(string $path): bool
    {
        if (file_exists($path)) {
            return false;
        }

        mkdir($path, 0755, true);

        return true;
    }

    /**
     * Creates a symbolic $link pointing to $file.
     *
     * @param string $link
     * @param string $file
     *
     * @return bool
     */
    public static function createSymlink(string $link, string $file): bool
    {
        if (!file_exists($link) && !file_exists(dirname($file).'/'.$link)) {
            return false;
        }

        if (file_exists($file)) {
            unlink($file);
        }

        return symlink($link, $file);
    }

    /**
     * Gets filenames and folders names (according to $option) inside a $path.
     *
     * @param string $path
     * @param int    $option
     *
     * @return string[]|null
     */
    public static function listDirectory(string $path, int $option = self::LIST_DIRECTORY_BOTH): ?array
    {
        if (!file_exists($path) || !is_dir($path)) {
            return null;
        }

        $aResult = [];

        $resourceDir = opendir($path);

        while (false !== ($strFile = readdir($resourceDir))) {
            if (in_array($strFile, ['.', '..'])) {
                continue;
            }

            $strCompleteFile = $path.'/'.$strFile;
            switch ($option) {
                case self::LIST_DIRECTORY_FILE_ONLY:
                    if (!is_dir($strCompleteFile)) {
                        $aResult[] = $strFile;
                    }
                    break;
                case self::LIST_DIRECTORY_DIR_ONLY:
                    if (is_dir($strCompleteFile)) {
                        $aResult[] = $strFile;
                    }
                    break;
                case self::LIST_DIRECTORY_BOTH:
                    $aResult[] = $strFile;
                    break;
                default:
                    return null;
            }
        }
        closedir($resourceDir);

        return $aResult;
    }
}
