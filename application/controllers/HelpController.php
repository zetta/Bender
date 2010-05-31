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
 * Muestra la ayuda para los comandos
 */
class HelpController extends BenderController
{
   /**
    * Muestra la ayuda
    * @param string $controller [OPTIONAL]
    */
   public function defaultAction()
   {
      $this->request->setFlag('no-truncate-text',true);
      $out = CommandLineInterface::getInstance();
      $out->printMessage("Bender Usage\n",'NOTE');
      $out->printMessage("./bender controller[:action] [arguments]\n\n",'INFO');
      if($this->controller)
      {
        $file = Formatter::slugToUpperCamelCase($this->controller).'Controller.php';
        if(!file_exists('application/controllers/'.$file))
           throw new Exception("There's no `{$this->controller}` controller");
        $help = new MetaDataFetcher($file);
        if(!class_exists($help->getClassName()))
           throw new Exception("There's no `{$this->controller}` controller");
        $help->showHelp();
      }
      else
      {
        foreach (new DirectoryIterator('application/controllers') as $fileInfo) {
          if($fileInfo->isDot() || $fileInfo->isDir()) continue;
          $help = new MetaDataFetcher($fileInfo->getFilename());
          if(class_exists($help->getClassName()))
            $help->showHelp();
        }
      }
      $out->printText("\nBender v". Bender::VERSION ." \n",'NOTE');
   }
 
 
 
     
}
