<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


abstract class PhpGenerator extends CodeGenerator
{

    /**
     * sql data types => php data types
     */
    protected $dataTypes = array(
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
    
    /**
     * sql data types => type
     */
    protected $types = array(
        'date' => 'date/time',
        'date_time' => 'date/time',
        'timestamp' => 'date/time',
        'time' => 'date/time',
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
    
    /**
     * sql data types => custom format
     */
     protected $formats = array(
        'date' => 'YYYY-MM-dd',
        'date_time' => 'YYYY-MM-dd HH:mm:ss',
        'timestamp' => 'YYYY-MM-dd HH:mm:ss',
        'time' => 'HH:mm:ss'
     );
    
    /**
     * mysql data types => if need to cast the data
     */
    protected $castDataTypes = array(
         'date' => 'Zend_Date'
    );

}
