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
 * Clase que genera los Collections
 */
class FactoryGenerator extends BaseGenerator implements CodeGenerable
{
  
  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/models/factories/%sFactory.php";

  public $requiredFlags = 'use-factories';

  /**
   * run
   */
  public function run()
  {
  	$fields = new BenderFieldCollection();
  	$fields->merge($this->table->getFields());
  	
  	$primaryFields = new BenderFieldCollection();
  	if($this->table->hasPrimaryField())
      $primaryFields->append($this->table->getPrimaryField());
  	
  	$table = clone $this->table;
  	while ($table->getExtends()){
  		$table = $table->getExtendedTable();
  		$fields->merge($table->getFields());
  		if($this->table->hasPrimaryField())
          $primaryFields->append($table->getPrimaryField());
  	}
  	
  	$this->view->table = $this->table;
    $this->view->fields = $fields;
    $this->view->primaryFields = $primaryFields;
  }
  

  
}
