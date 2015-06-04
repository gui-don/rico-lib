<?php

namespace Lib;

/**
 * Tools to handle files & folders
 */
abstract class File
{

	const LIST_DIRECTORY_FILE_ONLY = 1;
	const LIST_DIRECTORY_DIR_ONLY = 2;
	const LIST_DIRECTORY_BOTH = 3;

	/**
	 * Get all files and folders inside a folder
	 * @param string $strPath
	 * @param int $nOption
	 * @return string[]
	 */
	public static function listDirectory($strPath, $nOption = self::LIST_DIRECTORY_BOTH)
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
			switch ($nOption) {
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
