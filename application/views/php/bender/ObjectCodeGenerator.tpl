<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "application/lib/generators/{{ lang }}/{{ mode }}/BaseGenerator.php";


/**
 * Clase que genera los Objetos principales
 */
class ObjectGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/entities/%s.pseudoCode";
   
  /**
   * Runnable method 
   */
  public function run()
  {
  	  $this->view->fields = $this->table->getFields();
  }
  
 
}
