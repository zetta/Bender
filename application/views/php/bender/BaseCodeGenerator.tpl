<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


abstract class BaseGenerator extends {{ Lang }}Generator
{

  public function start()
  {
     $this->view->Class = $this->table->getObject();
     # place code here
  }
  
  public function getFileName()
  {
      return sprintf($this->fileName, $this->table->getObject());
  }

}




