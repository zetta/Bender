<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "application/lib/generators/php/default/BaseGenerator.php";


/**
 * Clase que genera los Beans
 */
class FormGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/views/forms/%s.tpl";
   
  /**
   * XXX Experimental. 
   * Encontrar una mejor manera de manejar los formularios, con Zend_Form, Zend_Filter, y Zend_Validate
   */
  public function run()
  {
  	$this->view->table = $this->table;
    $this->view->foreignKeys = $this->table->getForeignKeys();
    $this->view->primaryField = $this->table->getPrimaryField();
    $this->view->nonForeignKeys = $this->table->getNonForeignKeys();
  }
  
 
}
