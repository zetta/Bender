<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "application/lib/generators/dot/default/BaseGenerator.php";


/**
 * Class Diagram generator
 */
class ClassDiagramGenerator  extends BaseGenerator implements CodeGenerable
{
 
  /**
   * fileName
   */ 
  protected $fileName = "doc/class-diagram.dot";
  
  /**
   * Main method
   */
  public function run()
  { 
    $this->view->tables = BenderDatabase::getInstance()->getTables();
  }


  
}
