<?php
/**
 * ObjectGenerator
 * @author <zetta> <chentepixtol>
 */

require_once "application/lib/generators/php/ci/BaseGenerator.php";


/**
 * Clase que genera los Objetos principales
 */
class ObjectGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/models/%s.php";
   
  /**
   * Runnable method 
   */
  public function run()
  {
  	  $this->view->fields = $this->table->getFields();
  	  $this->view->nonPrimaryFields = $this->table->getNonPrimaryFields();
  }
  
 
}
