<?php

namespace Rico\Lib;

abstract class FileUtils
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
    public static function listDirectory(string $path, int $option = self::LIST_DIRECTORY_BOTH): array
    {
        if (!file_exists($path) || !is_dir($path)) {
            return false;
        }

        $aResult = [];

        $resourceDir = @opendir($path);
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
                    if (is_dir($strCompleteFile)) {
                        $aResult['directory'][] = $strFile;
                    } else {
                        $aResult['file'][] = $strFile;
                    }
                    break;
                default:
                    return false;
            }
        }
        closedir($resourceDir);

        return $aResult;
    }

    /**
     * Creates the completer $path with all missing intermediates directories.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function createPath(string $path): bool
    {
        if (!is_string($path)) {
            return false;
        }

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
        if (!is_string($file) || !is_string($link)) {
            return false;
        }

        if (!file_exists($link) && !file_exists(dirname($file).'/'.$link)) {
            return false;
        }

        if (file_exists($file)) {
            unlink($file);
        }

        return symlink($link, $file);
    }

    /**
     * Counts the number of lines in a $file.
     *
     * @param string $file
     * @param bool   $countEmpty
     *
     * @return int|bool
     */
    public static function count(string $file, bool $countEmpty = false)
    {
        if (!is_string($file) || !file_exists($file)) {
            return false;
        }

        $lines = 0;
        $handle = fopen($file, 'r');
        while (!feof($handle)) {
            $line = fgets($handle, 4096);
            $lastChar = strlen($line) - 1;

            if ($lastChar == 0) {
                if ($countEmpty) {
                    ++$lines;
                }
                continue;
            }

            if ($line[$lastChar] == "\n" || $line[$lastChar] == "\r") {
                ++$lines;
            }
        }

        fclose($handle);

        return $lines;
    }

    /**
     * Adds a new $line at the end of a $file without duplication.
     *
     * @param string $file
     * @param string $line
     *
     * @return bool|null
     */
    public static function addLine(string $file, string $line)
    {
        if (!file_exists($file)) {
            return null;
        }

        $handle = fopen($file, 'r+');
        while (($currentLine = fgets($handle)) !== false) {
            if (trim($currentLine) == $line) {
                return false;
            }
        }

        fwrite($handle, $line.PHP_EOL);

        fclose($handle);

        return true;
    }
}
