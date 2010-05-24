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
 * BaseLibraryGenerator
 */
abstract class BaseLibraryGenerator extends PhpGenerator
{
  
  /**
   * start
   */
  public function start()
  {
    $this->view->fileName = $this->fileName;
  }
  
  /** 
   * get file name
   */
  public function getFileName()
  {
      return $this->fileName;
  }

  /**
   * Run method
   */
  public function run()
  {
      ## run its empty 'cause the library dont need to iterate over the fields (actually don't have table)
  }

}











