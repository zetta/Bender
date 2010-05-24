<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



class BenderMysqlPDO implements BenderPDOInterface
{
  
  /**
   * Caller PDO
   * @var BenderPDO
   */
  private $pdo;
  
  /**
   * obtanins the columns in the table
   * @param string $tableName
   * @return array 
   */
  public function getColumnsFromTable($tableName)
  {
    return $this->pdo->query("SHOW FULL COLUMNS FROM {$tableName} WHERE TRUE");
  }
  
  /**
   * Set the PDO container
   * @param BenderPDO $pdo
   */
  public function setContainer(BenderPDO $pdo)
  {
    $this->pdo = $pdo;
  }
  
  /**
   * Get all the tables in the database
   * @return array
   */
  public function showFullTables()
  {
    return $this->pdo->query("SHOW FULL TABLES");
  }
  
}
