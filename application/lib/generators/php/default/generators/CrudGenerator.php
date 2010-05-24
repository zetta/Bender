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
class CrudGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/controllers/%sController.php";
  
  /**
   * Required Option in schemaFile 
   */
  public $requiredOption = 'generate-crud';


  /**
   * Runnable method 
   * Experimental
   */
  public function run()
  {
  	$this->view->formatter = new Formatter();
  	$this->view->primaryField = $this->table->getPrimaryField();
  	$this->view->table = $this->table;
    $this->view->fields = $this->table->getFields();
    $this->view->nonPrimaryFields = $this->table->getNonPrimaryFields();
    $this->view->uniqueField = $this->table->getUniqueFields()->getOne();
    $this->request->setFlag('generate-cruds',true);
  }
  
  /**
   * @return string $fileName donde se guardara la informacion extra
   */
  public function additionalList()
  {
    $this->run();
    return "application/views/{$this->table->getLowerObject()}/List.tpl";
  }
  
  /**
   * @return string $fileName donde se guardara la informacion extra
   */
  public function additionalEdit()
  { 
    $this->run();
    return "application/views/{$this->table->getLowerObject()}/Edit.tpl";
  }
  
  /**
   * @return string $fileName donde se guardara la informacion extra
   */
  public function additionalRow()
  {
    $this->run();
    return "application/views/{$this->table->getLowerObject()}/_row.tpl";
  }
  
 
}
