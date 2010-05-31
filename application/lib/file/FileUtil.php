<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FileUtil
 */
class FileUtil
{
    
    /**
     * Copy directory contents
     * @param $source
     * @param $destination
     */
    function copydir($source, $destination)
    {
        if (! is_dir($destination))
        {
            mkdir($destination, 0755, true);
        }
        $handle = opendir($source);
        while ( ($file = readdir($handle)) )
        {
            if ($file == "." && $file == "..")
                continue;
            $path = "$source/$file";
            if (is_dir($file))
                $this->copydir($path,"$destination/$file");
            else
                copy($path, "$destination/$file");
        }
        closedir($handle);
    }

}
