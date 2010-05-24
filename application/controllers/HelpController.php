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
    * utilice help controller para ver la ayuda de un controlador en especifico
    * @param string $controller [OPTIONAL]
    */
   public function defaultAction()
   {
      $command = $this->request->getArg(0);
      $this->request->setFlag('no-truncate-text',true);
      $out = CommandLineInterface::getInstance();
      $out->printMessage("Bender Usage\n",'NOTE');
      $out->printMessage("./bender controller[:action]\n\n",'INFO');
      if($command)
      {
        $file = Formatter::slugToUpperCamelCase($command).'Controller.php';
        if(!file_exists('application/controllers/'.$file))
           throw new Exception("There's no `{$command}` controller");
        $help = new HelpFetcher($file);
        if(!class_exists($help->getClassName()))
           throw new Exception("There's no `{$command}` controller");
        $help->showHelp();
      }
      else
      {
        foreach (new DirectoryIterator('application/controllers') as $fileInfo) {
          if($fileInfo->isDot() || $fileInfo->isDir()) continue;
          $help = new HelpFetcher($fileInfo->getFilename());
          if(class_exists($help->getClassName()))
            $help->showHelp();
        }
      }
      $out->printMessage("Bender v". Bender::VERSION ." \n",'INFO');
   }
 
 
 
     
}
