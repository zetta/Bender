<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BenderPacker extends ZipArchive
{
  private $lang;
  private $pattern;
  
  /**
   * @param string
   */
  public function setLang($lang)
  {
    $this->lang = $lang;
  } 
  
  /**
   * @param string
   */
  public function setPattern($pattern)
  {
    $this->pattern = $pattern;
  }
  
  /**
   * Pack desired script
   */
  public function pack()
  {
     $this->open("Bender.{$this->lang}.{$this->pattern}.package.zip",ZIPARCHIVE::CREATE);
     $dir = "application/lib/generators/{$this->lang}/{$this->pattern}";
     if(!is_dir($dir))
       throw new Exception('No such `lang` or `pattern`');
     $this->addDir($dir);
     $dir = "application/views/{$this->lang}/{$this->pattern}";
     $this->addDir($dir);
     $this->close();
  }
  
  /**
   * handles directories recursively
   * Based on "peter at boring dot ch" at http://www.php.net/manual/en/function.ziparchive-addfile.php#93090" 
   */
  public function addDir($path) { 
    $this->addEmptyDir($path); 
    $nodes = glob($path . '/*'); 
    foreach ($nodes as $node) { 
        if (is_dir($node)) { 
            $this->addDir($node); 
        } else if (is_file($node))  { 
            $this->addFile($node); 
        } 
    } 
  } 
  
  
}
