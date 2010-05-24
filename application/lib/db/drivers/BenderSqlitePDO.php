<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'application/lib/db/BenderDatabaseException.php';

class BenderSqlitePDO implements BenderPDOInterface
{
  
  /**
   * Caller pdo
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
    $info = $this->pdo->query("SELECT sql FROM sqlite_master WHERE type = 'table' AND tbl_name = '{$tableName}'")->fetch();
    if($info === FALSE)
        $result = FALSE;
    else
    {
        $sql = $info['sql'];
        $fields = array();
        $result = array();
        preg_match_all('/\((.*)\)/i',$sql,$fields);
        $fields = explode(',',$fields[1][0]);
        foreach($fields as $fieldInfo)
        {
           $fieldInfo = trim($fieldInfo);
           list($name,$type) = explode(' ',$fieldInfo,2);
           $pri = 'PRIMARY KEY';
           $index = (strstr($type,$pri)) ? 'PRI' : '';
           $rs = $this->pdo->query("SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name = '{$tableName}' AND sql LIKE '%{$name}%'");
           foreach($rs as $r)
           {
              $index = (strpos($r['sql'],'UNIQUE')) ? 'UNI' : $index;
           }
           $result[] = array(
             'Field' => $name,
             'Type' =>  trim(strstr($type,$pri) ? str_replace($pri,'',$type) : $type) ,
             'Null' => 'YES',
             'Default' => null,
             'Key' => $index,
             'Comment' => null
           );
        }
    }
    return $result;
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
    return  $this->pdo->query("SELECT tbl_name FROM sqlite_master WHERE type='table' ");
  }
  
}








