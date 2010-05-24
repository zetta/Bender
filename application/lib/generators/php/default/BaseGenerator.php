<?php 
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */





abstract class BaseGenerator extends PhpGenerator
{

  public function start()
  {
    $object = $this->table->getObject();
    $lowerObject = $this->table->getLowerObject();
    $this->view->Bean = $object;
    $this->view->bean = $lowerObject;
    $this->view->Catalog = $object."Catalog";
    $this->view->catalog = $lowerObject."Catalog";
    $this->view->Collection = $object."Collection";
    $this->view->collection = $lowerObject."Collection";
    $this->view->Exception = $object."Exception";
    $this->view->Validator = $object."Validator";
    $this->view->validator = $lowerObject."Validator";
    $this->view->Factory = $object."Factory";
    $this->view->factory = $lowerObject."Factory";
    $this->view->tableName = $this->table->getTableName();
    $this->view->table = $this->table;
    
    // check flags  
    $this->view->fileName = $this->fileName;
  }
  
  public function getFileName()
  {
      return sprintf($this->fileName, $this->table->getObject());
  }


}











