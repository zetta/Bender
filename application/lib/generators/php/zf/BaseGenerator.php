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

    /**
     * WakeUp method
     */
    public function wakeUp()
    {
        $this->castDataTypes = array(
            'date' => 'string',
            'date_time' => 'string',
            'timestamp' => 'string',
            'time' => 'string',
            'varchar' => 'string',
            'int' => 'int',
            'integer' => 'int',
            'numeric' => 'int',
            'text' => 'string',
            'float' => 'float',
            'smallint' => 'int',
            'decimal' => 'int',
            'tinyint' => 'int'
        );
    }
    
    public function start()
    {
        $this->view->Class = 'Application_Model_'.$this->table->getObject();
        $this->view->Mapper = 'Application_Model_'.$this->table->getObject().'Mapper';
        $this->view->DbTable = 'Application_Model_DbTable_'.$this->table->getObject();
        $this->view->Object = $this->table->getObject();
        $this->view->object = $this->table->getLowerObject();
        $this->view->table = $this->table;
    }
  
    public function getFileName()
    {
        return sprintf($this->fileName, $this->table->getObject());
    }

}




