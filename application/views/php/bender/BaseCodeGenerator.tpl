<?php
/**
 * ObjectGenerator
 * @author {{ author }}
 */


abstract class BaseGenerator extends {{ Lang }}Generator
{

  public function start()
  {
     $this->view->Class = $this->table->getObject();
     # place code here
  }
  
  public function getFileName()
  {
      return sprintf($this->fileName, $this->table->getObject());
  }

}




