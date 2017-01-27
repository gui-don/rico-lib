<?php

declare(strict_types=1);

namespace Rico\Slib;

abstract class FileUtils
{
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
            $lastChar = strlen((string) $line) - 1;

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
