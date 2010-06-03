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
       $this->view->Class = $this->table->getObject();
       $this->view->class = $this->table->getLowerObject();
       $this->view->table = $this->table;
    }
  
    public function getFileName()
    {
        return sprintf($this->fileName, strtolower($this->table->getObject()));
    }

}




