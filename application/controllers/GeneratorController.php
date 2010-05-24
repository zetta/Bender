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
 * Modulo para crear nuevos generadores 
 */
class GeneratorController extends BenderController
{    

    /**
     * Genera un nuevo script
     * @param string $lang
     * @param string $pattern [OPTIONAL]
     */
    public function createNewAction()
    {
        $this->lang = $this->request->getArg(0);
        if($this->lang === null)
          throw new InvalidArgumentException("Must specify a language");
        $this->mode = $this->request->getArg(1,'default');
        
        $path = "application/lib/generators/{$this->lang}/{$this->mode}";
        if(is_dir($path))
          throw new Exception("Lang [{$this->lang}] mode [{$this->mode}] already exists");
        $this->createStructure();
        $this->forward('model:generate', array('php','bender',100 => $this->lang, 101 => $this->mode), array(
            'output-dir' => "application/lib/generators/{$this->lang}/{$this->mode}",
            'ignore-database' => true
        ));
    }
    
    /**
     * Elimina un script de Bender
     * @param string $lang
     * @param string $pattern
     */
    public function removeAction()
    {
        $this->lang = $this->request->getArg(0);
        if($this->lang === null)
          throw new InvalidArgumentException("Must specify a language");
        $this->mode = $this->request->getArg(1);
        if($this->lang === null)
          throw new InvalidArgumentException("Must specify a pattern");
        
        $dumper = new BenderDumper();
        $dumper->deleteDirectoryContent("application/lib/generators/{$this->lang}/{$this->mode}");
        $dumper->deleteDirectoryContent("application/views/{$this->lang}/{$this->mode}");
    }
    
    
    
    /**
     * Create the directory structure
     */
    private function createStructure()
    {
      $paths = array(
         "application/lib/generators/{$this->lang}/{$this->mode}/libs",
         "application/lib/generators/{$this->lang}/{$this->mode}/generators",
         "application/views/{$this->lang}/{$this->mode}/libs"
      );
      CommandLineInterface::getInstance()->printSection('Generator', 'Creating directory structure');
      foreach ($paths as $path){
          mkdir($path,0755,true);
      }
    }
    


}








