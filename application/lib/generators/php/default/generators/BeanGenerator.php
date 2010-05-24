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
class BeanGenerator extends BaseGenerator implements CodeGenerable
{

  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/models/beans/%s.php";
   
  /**
   * Runnable method 
   */
  public function run()
  {
  	$this->view->status = $this->table->getFields()->searchField('/status/i');
    $this->view->fields = $this->table->getFields();
  }
  
 
}
