<?php
/**
 * ObjectGenerator
 * @author {{ author }}
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
