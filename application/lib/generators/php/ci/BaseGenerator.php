<?php
/**
 * ObjectGenerator
 * @author <zetta> <chentepixtol>
 */


abstract class BaseGenerator extends PhpGenerator
{

  public function start()
  {
     $this->view->Class = $this->table->getObject();
     $this->view->class = $this->table->getLowerObject();
     $this->view->table = $this->table;
  }
  
  public function getFileName()
  {
      return sprintf($this->fileName, strtolower($this->table->getObject()));
  }

}




