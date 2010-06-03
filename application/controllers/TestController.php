<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class TestController extends BenderController
{


    /**
     * Realiza los test unitarios
     */
    public function unitAction()
    {   
        passthru('phpunit');
    }
    
    /**
     * Entra a modo interactivo
     */
    public function interactiveAction()
    {
      do{
        $entry = CommandLineInterface::getInstance()->prompt('>');
        eval($entry);
        echo "\n";
      }
      while ($entry != 'quit;');
      echo 'bye!';
    }
    
}
