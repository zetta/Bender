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
 * FileSaver
 */
class FileSaver
{
    /**
     * where all the output files will be
     */
    private $outputDir = "output";
    private static $count = 0;
    /**
     * Codificación utilizada en los archivos que se generarán
     * @var string
     */
    private $encoding = 'UTF-8';
    
    /**
     * 
     * @param string $encoding
     */
    public function setEncoding($encoding){
    	$this->encoding = $encoding;
    }
  
   /**
     * Escribe el archivo en la dirección especificada
     * @param string $file
     * @param string $content
     */
    public function saveFile($file, $content)
    { 
        $this->checkOutputDir();
        $file = $this->outputDir. DIRECTORY_SEPARATOR . $file;
        if(is_dir($file))
          throw new Exception($file.' is a directory ');
        $this->doSave($file,$content);
    }
    
    /**
     * doSave method
     * @param string $path
     * @param string $content
     */
    private function doSave($file, $content)
    {
      CommandLineInterface::getInstance()->printSection('Saver', 'S ' . $file, 'NOTE');
      $dir = dirname($file);
      if (!is_dir($dir))
          mkdir($dir, 0777, true);
      $handle = fopen($file, "w");      
      if ($this->encoding != 'UTF-8')
          $content = iconv("UTF-8", $this->encoding, $content);
      
      fwrite($handle, $content);
      fclose($handle);
      self::$count ++;
    }
    
    /**
     * Check if the output dir has been changed
     */
    private function checkOutputDir()
    {
      $request = BenderRequest::getInstance();
      $this->outputDir = ( $request->getFlag('output-dir') ) ? $request->getFlag('output-dir') : $this->outputDir;
    }
    
    /**
     * @param string
     */
    public function setOutputDir($dir)
    {
      $this->outputDir = $dir;
    }
    
    /**
     * get the count of the saved files
     */
    public function getCount()
    {
      return self::$count;
    }

}
