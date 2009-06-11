<?php 




class CacheController extends GenericController 
{

    /**
     * Limpia el caché generado y las clases =)  
     */
    public function clearAction()
    {
        $this->deleteDirectoryContent('output');
        $this->deleteFile('application/data/autoloadCache.file');
    }
    
    /**
     * Elimina los contenidos de un directorio
     * @param string $path 
     * @param boolean $deleteItself
     */ 
    private function deleteDirectoryContent($path,$deleteItself = false)
    {
	    if(!$dh = @opendir($path)) 
	        return;
	    while (false !== ($obj = readdir ($dh)))
	    {
	        if($obj == '.' || $obj == '..' || $obj == '.svn') continue;
		    $file = $path . '/' . $obj;
		    if(is_dir($file))
		    {
		        $this->deleteDirectoryContent($file,true);
		    }else{
		        $this->deleteFile($file);
		    }
	    }
	    closedir ($dh);
	    if($deleteItself)
	    {
	        CommandLineInterface::getInstance()->printSection('Cache', "Deleting {$path}", 'NOTE');
    	    @rmdir ($path);
    	}
    }
    
    /**
     * Elimina un archivo
     * @param string $filePath
     */
    private function deleteFile($filePath)
    {
        @unlink ($filePath);
		CommandLineInterface::getInstance()->printSection('Cache', "Deleting {$filePath}", 'NOTE');
    }
    
}
