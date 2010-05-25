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
     * @param string $pattern [OPTIONAL] (default)
     */
    public function createNewAction()
    {
        $path = "application/lib/generators/{$this->lang}/{$this->pattern}";
        if(is_dir($path))
          throw new Exception("Lang [{$this->lang}] mode [{$this->pattern}] already exists");
        $this->createStructure();
        $this->forward('model:generate', array('php','bender',100 => $this->lang, 101 => $this->pattern), array(
            'output-dir' => "application/lib/generators/{$this->lang}/{$this->pattern}",
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
        $dumper = new BenderDumper();
        $dumper->deleteDirectoryContent("application/lib/generators/{$this->lang}/{$this->pattern}");
        $dumper->deleteDirectoryContent("application/views/{$this->lang}/{$this->pattern}");
    }
    
    /**
     * Clona un script
     * @param string $lang
     * @param string $pattern
     */
    public function cloneAction()
    {
       
    }
    
    
    
    /**
     * Create the directory structure
     */
    private function createStructure()
    {
      $paths = array(
         "application/lib/generators/{$this->lang}/{$this->pattern}/libs",
         "application/lib/generators/{$this->lang}/{$this->pattern}/generators",
         "application/views/{$this->lang}/{$this->pattern}/libs"
      );
      CommandLineInterface::getInstance()->printSection('Generator', 'Creating directory structure');
      foreach ($paths as $path){
          mkdir($path,0755,true);
      }
    }
    


}








