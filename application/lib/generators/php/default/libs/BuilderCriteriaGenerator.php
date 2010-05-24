<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "application/lib/generators/php/default/BaseLibraryGenerator.php";


/**
 * BuilderCriteria generator
 */
class BuilderCriteriaGenerator  extends BaseLibraryGenerator implements CodeGenerable
{
	
  
  protected $fileName = "lib/search/BuilderCriteria.php";
  
  public function run()
  { 
  	$this->view->tables = BenderDatabase::getInstance()->getTables();
  }


  
}
