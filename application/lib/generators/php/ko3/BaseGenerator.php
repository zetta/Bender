<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


abstract class BaseGenerator extends PhpGenerator
{

  /**
   * start
   */
  public function start()
  {
     $this->view->Class = 'Model_'.$this->table->getObject();
     $this->view->table = $this->table;
  }
  
  /**
   * return the file name
   * @return string
   */
  public function getFileName()
  {
      return sprintf($this->fileName, strtolower($this->table->getObject()));
  }

}




