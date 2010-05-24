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
class ValidatorGenerator extends BaseGenerator implements CodeGenerable
{
  
  /**
   * Donde se va a guardar el archivo
   */
  protected $fileName = "application/models/validators/%sValidator.php";

  /**
   * @var mixed $requiredFlags
   */    
  public $requiredFlags = 'use-validators';

  /**
   * run
   */
  public function run()
  {
    $this->view->fields = $this->table->getFields();
  }
  

  
}
