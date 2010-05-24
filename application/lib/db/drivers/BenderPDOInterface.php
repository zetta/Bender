<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * PDO Driver Interface
 */
interface BenderPDOInterface
{


  /**
   * obtanins the columns in the table
   * @param string $tableName
   * @return array
   *         The returned array MUST contain the fields in MYSQL "SHOW FULL COLUMNS" format:
   *         <a href="http://dev.mysql.com/doc/refman/5.1/en/show-columns.html">View Show columns information</a>
   *         <pre>
   *         Array
   *         (
   *           [Field] => fieldName
   *           [Type] => fieldType
   *           [Collation] => 
   *           [Null] => NO|YES
   *           [Key] => PRI|UNI|MUL
   *           [Default] => CURRENT_TIMESTAMP   # Default value for the field
   *           [Comment] => The field comment
   *         )
   *         </pre>
   */
  public function getColumnsFromTable($tableName);
  
  /**
   * Set the PDO container
   * @param BenderPDO $pdo
   */
  public function setContainer(BenderPDO $pdo);

  /**
   * Get all the tables in the database
   * @return array
   *         The returned array MUST contain the fields in MYSQL "SHOW FULL TABLES" format:
   *         <a href="http://dev.mysql.com/doc/refman/5.1/en/show-tables.html">View Show tables information</a>
   *         <pre>
   *         Array
   *         (
   *           [0] => TABLE_NAME
   *         )
   *         </pre>
   */
  public function showFullTables();
  



}
