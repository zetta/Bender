<?php
/**
 * ObjectGenerator
 * @author <zetta> <chentepixtol>
 */


abstract class BaseGenerator extends PhpGenerator
{

  /**
   * start
   */
  public function start()
  {
     $this->view->Class = 'Model_'.$this->table->getObject();
     $this->view->table = $this->table;
  }
  
  /**
   * return the file name
   * @return string
   */
  public function getFileName()
  {
      return sprintf($this->fileName, strtolower($this->table->getObject()));
  }

}




