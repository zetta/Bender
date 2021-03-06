<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
class BenderDumper
{

    /**
     * Elimina los contenidos de un directorio
     * @param string $path 
     * @param boolean $deleteItself
     */ 
    public function deleteDirectoryContent($path,$deleteItself = false)
    {
	    if(!$dh = @opendir($path)) 
	        return;
	    while (false !== ($obj = readdir ($dh)))
	    {
	        if(preg_match('/^\./',$obj)) continue;
		    $file = $path . '/' . $obj;
		    if(is_dir($file))
		    {
		        $this->deleteDirectoryContent($file,true);
		    }else{
		        $this->deleteFile($file);
		    }
	    }
	    closedir($dh);
	    if($deleteItself)
	    {
	        CommandLineInterface::getInstance()->printSection('Dump', "D {$path}", 'NOTE');
    	    @rmdir($path);
    	}
    }
    
    /**
     * Elimina un archivo
     * @param string $filePath
     */
    public function deleteFile($filePath)
    {
        @unlink ($filePath);
		    CommandLineInterface::getInstance()->printSection('Dump', "D {$filePath}", 'NOTE');
    }


}
