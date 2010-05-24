<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



class BenderPDO extends PDO
{

  /**
   * @var BenderPDOInterface
   */
  private $driver;
  
  
  /**
   * Class Constructor
   */
  public function BenderPDO( $dsn, $username, $password)
  {
    parent::__construct($dsn,$username,$password);
    $this->driver = BenderPDO::factory($dsn);
    $this->driver->setContainer($this);
  }
  
  /**
   * @return BenderPDOInterface
   */
  public static function factory($dsn)
  {
    $dsna = explode(':',$dsn,2);
    $driver = array_shift($dsna);
    $obj = null;
    switch(strtolower($driver))
    {
      case 'mysql':
       $obj = new BenderMysqlPDO();
      break;
      case 'sqlite':
       $obj = new BenderSqlitePDO();
      break;
      default:
       throw new BenderDatabaseException("Driver not supported");
    }
    return $obj;
  }
  
  /**
   * obtanins the columns in the table
   * @param string $tableName
   * @return array 
   */
  public function getColumnsFromTable($tableName)
  {
    return $this->driver->getColumnsFromTable($tableName);
  }
  
  /**
   * Get all the tables in the database
   * @return array
   */
  public function showFullTables()
  {
    return $this->driver->showFullTables();
  }
  
  
}
