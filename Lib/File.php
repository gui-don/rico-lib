<?php

namespace Rico\Lib;

/**
 * Tools to handle files & folders
 */
abstract class File
{

    const LIST_DIRECTORY_FILE_ONLY = 1;
    const LIST_DIRECTORY_DIR_ONLY = 2;
    const LIST_DIRECTORY_BOTH = 3;

    /**
     * Get filenames and folders names inside a directory
     * @param string $strPath
     * @param int $option
     * @return string[]
     */
    public static function listDirectory($strPath, $option = self::LIST_DIRECTORY_BOTH)
    {
        if(!file_exists($strPath) || !is_dir($strPath)) {
            return false;
        }

        $aResult = array();

        $resourceDir = @opendir($strPath);
        while (false !== ($strFile = readdir($resourceDir))) {
            if (in_array($strFile, array('.', '..'))) {
                continue;
            }

            $strCompleteFile = $strPath.'/'.$strFile;
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
}
