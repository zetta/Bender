<?php

/**
 * Muestra la ayuda para los comandos
 */
class HelpController extends GenericController
{
   /**
    * Show this help
    */
   public function defaultAction()
   {
      $out = CommandLineInterface::getInstance();
      $out->printMessage("Bender Usage\n",'NOTE');
      $out->printMessage("bender.php controller:action\n",'INFO');
      
      foreach (new DirectoryIterator('application/controllers') as $fileInfo) {
        if($fileInfo->isDot() || $fileInfo->isDir()) continue;
        $help = new HelpFetcher($fileInfo->getFilename());
        $help->showHelp();
      }
      $out->printMessage("Bender Modeler v". Bender::VERSION ." \n",'INFO');
   }
 
 
 
     
}
