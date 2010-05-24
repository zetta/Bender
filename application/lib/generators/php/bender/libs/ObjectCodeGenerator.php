<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "application/lib/generators/php/bender/BaseGenerator.php";


/**
 * Clase que genera los Objetos principales
 */
class ObjectCodeGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "generators/ObjectGenerator.php";
   
  /**
   * Runnable method 
   */
  public function run()
  {
    if($this->request->getArg(100) === null)
      $this->ignore();
  }
  
 
}
