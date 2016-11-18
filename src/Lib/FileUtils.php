<?php

namespace Rico\Lib;

abstract class FileUtils
{
    const LIST_DIRECTORY_FILE_ONLY = 1;
    const LIST_DIRECTORY_DIR_ONLY = 2;
    const LIST_DIRECTORY_BOTH = 3;

    /**
     * Get filenames and folders names inside a directory.
     *
     * @param string $path
     * @param int    $option
     *
     * @return string[]
     */
    public static function listDirectory(string $path, int $option = self::LIST_DIRECTORY_BOTH)
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
     * Create all missing directories from a path.
     *
     * @param string $path
     *
     * @return bool True if path has been created, false otherwise
     */
    public static function createPath(string $path)
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
     * Create a symbolic link.
     *
     * @param string $link Absolute or relative symbolic link
     * @param string $file
     *
     * @return bool True if symbolic link has been created, false otherwise
     */
    public static function createSymlink(string $link, string $file)
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
     * Count the number of lines in a file.
     *
     * @param string $file       Absolute or relative URL of the file
     * @param bool   $countEmpty Determine wheter empty lines are counted or not
     *
     * @return int Number of line in a file or false if an error occured
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
     * Add a new line at the end of a file without duplication.
     *
     * @param string $file File path and name
     * @param string $line Line to add
     *
     * @return bool True if the line has been added, false otherwise, null if an error occured
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
