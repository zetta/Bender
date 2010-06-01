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
 * Controlador de scripts
 */
class ScriptController extends BenderController
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
        $this->forward('script:run', array('php','bender',100 => $this->lang, 101 => $this->pattern), array(
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
        $dumper->deleteDirectoryContent("application/lib/generators/{$this->lang}/{$this->pattern}",true);
        $dumper->deleteDirectoryContent("application/views/{$this->lang}/{$this->pattern}",true);
    }

    /**
     * Clona un script
     * @param string $lang
     * @param string $pattern
     * @param string $newPattern
     */
    public function cloneAction()
    {
       $util = new FileUtil();
       $util->copydir("application/lib/generators/{$this->lang}/{$this->pattern}","application/lib/generators/{$this->lang}/{$this->newPattern}");
       $util->copydir("application/views/{$this->lang}/{$this->pattern}","application/views/{$this->lang}/{$this->newPattern}");
       CommandLineInterface::getInstance()->printSection('Script', 'cloned');
    }

    
    /**
     * Corre los scripts especificados para `lang` y `pattern`
     * @param string $lang
     * @param string $pattern [OPTIONAL] (default)
     */
    public function runAction()
    {
    	$runner = new GeneratorRunner($this->lang,$this->pattern);
        if(BenderRequest::getInstance()->getFlag('isolated'))
          $this->forward('cache:clear',null,array('keep-autoloader'=>true));
        $path = "application/lib/generators/{$this->lang}/{$this->pattern}/generators/";
        $runner->directoryIteration($path);
        $path = "application/lib/generators/{$this->lang}/{$this->pattern}/libs/";
        $runner->directoryIteration($path,true);
        $fs = new FileSaver();
        CommandLineInterface::getInstance()->printSection('End', $fs->getCount() . ' files generated');
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
      CommandLineInterface::getInstance()->printSection('Script', 'Creating directory structure');
      foreach ($paths as $path){
          mkdir($path,0755,true);
      }
    }
    
    
    
    

}


