<?php
/**
 * ObjectGenerator
 * @author <zetta> <chentepixtol>
 */

require_once "application/lib/generators/php/ko3/BaseGenerator.php";


/**
 * Clase que genera los Objetos principales
 */
class ObjectGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/classes/model/%s.php";
   
  /**
   * Runnable method 
   */
  public function run()
  {
  	  $this->view->fields = $this->table->getFields();
  	  $this->view->nonPrimaryFields = $this->table->getNonPrimaryFields();
  	  $this->view->uniqueFields = $this->table->getUniqueFields();
      $this->view->foreigns = $this->table->getForeignKeys();
  }
  
 
}
