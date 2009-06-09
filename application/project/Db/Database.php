<?php
# Name: Database.class.php
# File Description: MySQL Class to allow easy and clean access to common mysql commands
# Author: ricocheting
# Web: http://www.ricocheting.com/scripts/
# Update: 2/2/2009
# Version: 2.1
# Copyright 2003 ricocheting.com

/**
 * @author Modified by zetta
 */

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once 'application/project/Db/MysqlException.php';

/**
 * Database
 * @author ricocheting
 * @author zetta
 */
class Database
{
    
    private $server = ""; //database server
    private $user = ""; //database login name
    private $pass = ""; //database login password
    private $database = ""; //database name
    private $pre = ""; //table prefix
    

    //internal info
    private $record = array();
    
    private $error = "";
    private $errno = 0;
    
    //table name affected by SQL query
    private $field_table = "";
    
    //number of rows affected by SQL query
    private $affected_rows = 0;
    
    private $link_id = 0;
    private $query_id = 0;
    
    /**
     * Instance
     * @var Database
     */
    private static $instance = null;
    
    /**
     * Obtiene la instancia 
     * @return Database
     */
    public static function getInstance()
    {
        if (! isset(self::$instance))
        {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * blah blah
     *
     * @param string $server
     * @param string $user
     * @param string $pass
     * @param string $database
     * @param string $pre Prefix [OPTIONAL]
     */
    public function configure($server, $user, $pass, $database, $pre = '')
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->pre = $pre;
    }
    
    /**
     * Constructor
     * @return Database
     */
    private function Database()
    {
    }
    
    /**
     * connect and select database using vars above
     * @param boolean $new_link  can force connect() to open a new link, even if mysql_connect() was called before with the same parameters
     */
    public function connect($new_link = false)
    {
        $this->link_id = @mysql_connect($this->server, $this->user, $this->pass, $new_link);
        
        if (! $this->link_id)
        {
            throw new MysqlException("Could not connect to server: {$this->server}.\n".mysql_error());
        }
        
        if (! @mysql_select_db($this->database, $this->link_id))
        {
            throw new MysqlException("Could not open database: {$this->database}.\n".mysql_error());
        }
        $this->server = '';
        $this->user = '';
        $this->pass = '';
        $this->database = '';
    }
    
    /**
     * Close the connection
     */
    public function close()
    {
        if (! mysql_close($this->link_id))
        {
            throw new MysqlException("Connection close failed.\n".mysql_error());
        }
    }
    
    /**
     * escapes characters to be mysql ready
     * @param string $string
     * @return string
     */
    private function escape($string)
    {
        if (get_magic_quotes_gpc())
            $string = stripslashes($string);
        return mysql_real_escape_string($string);
    }
    
    /**
     * executes SQL query to an open connection
     * @param string $sql
     * @return resource id
     */
    public function query($sql)
    {
        $this->query_id = @mysql_query($sql, $this->link_id);
        if (! $this->query_id)
        {
            throw new MysqlException("MySQL Query fail: $sql\n".mysql_error());
        }
        
        $this->affected_rows = @mysql_affected_rows();
        
        return $this->query_id;
    }
    
    /**
     * fetches and returns results one line at a time
     *
     * @param int $query_id for mysql run. if none specified, last used
     * @return array fetched record(s)
     */
    public function fetch_array($query_id = -1)
    {
        if ($query_id != - 1)
        {
            $this->query_id = $query_id;
        }
        
        if (isset($this->query_id))
        {
            $this->record = @mysql_fetch_assoc($this->query_id);
        } else
        {
            throw new MysqlException("Invalid query_id: {$this->query_id}. Records could not be fetched.\n".mysql_error());
        }
        
        if ($this->record)
        {
            $this->record = array_map("stripslashes", $this->record);
        }
        return $this->record;
    }
    
    /**
     * returns all the results (not one row)
     *
     * @param string $sql (MySQL query) the query to run on server
     * @return array $out assoc array of ALL fetched results
     */
    public function fetch_all_array($sql)
    {
        $query_id = $this->query($sql);
        $out = array();
        
        while (($row = $this->fetch_array($query_id, $sql)))
        {
            $out[] = $row;
        }
        
        $this->free_result($query_id);
        return $out;
    }
    
    /**
     * frees the resultset
     * @param int $query_id query_id for mysql run. if none specified, last used
     */
    public function free_result($query_id = -1)
    {
        if ($query_id != - 1)
        {
            $this->query_id = $query_id;
        }
        if (! @mysql_free_result($this->query_id))
        {
            throw new MysqlException("Result ID: <b>{$this->query_id}</b> could not be freed.\n".mysql_error());
        }
    }
    
    /**
     * does a query, fetches the first row only, frees resultset
     *
     * @param string $query_string  (MySQL query) the query to run on server
     * @return array $out array of fetched results
     */
    public function query_first($query_string)
    {
        $query_id = $this->query($query_string);
        $out = $this->fetch_array($query_id);
        $this->free_result($query_id);
        return $out;
    }
    
    /**
     * does an update query with an array
     *
     * @param string $table  table (no prefix)
     * @param array $data  assoc array with data (doesn't need escaped)
     * @param string $where where condition
     * @return int resource_id
     */
    public function query_update($table, $data, $where = '1')
    {
        $q = "UPDATE `" . $this->pre . $table . "` SET ";
        
        foreach ( $data as $key => $val )
        {
            if (strtolower($val) == 'null')
                $q .= "`$key` = NULL, ";
            elseif (strtolower($val) == 'now()')
                $q .= "`$key` = NOW(), ";
            else
                $q .= "`$key`='" . $this->escape($val) . "', ";
        }
        
        $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';
        
        return $this->query($q);
    }
    
    /**
     * does an insert query with an array
     *
     * @param string $table table name
     * @param array $data  assoc array with data
     * @return int id of inserted record, false if error
     */
    public function query_insert($table, $data)
    {
        $q = "INSERT INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';
        
        foreach ( $data as $key => $val )
        {
            $n .= "`$key`, ";
            if (strtolower($val) == 'null')
                $v .= "NULL, ";
            elseif (strtolower($val) == 'now()')
                $v .= "NOW(), ";
            else
                $v .= "'" . $this->escape($val) . "', ";
        }
        
        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";
        
        if ($this->query($q))
        {
            return mysql_insert_id();
        } else
            return false;
    
    }
    
    /**
     * returns id of inserted record
     * @return int
     */
    public function getLastInsertId()
    {
        return mysql_insert_id();
    }
    
    /**
     * GetNumFields
     *
     * @param int $query_id resource
     * @return int|boolean 
     */
    public function getNumFields($query_id = 0)
    {
        if (! $query_id)
            $query_id = $this->query_id;
        
        if ($query_id)
            return mysql_num_fields($query_id);
        else
            return false;
    }
    
    /**
     * get Number of  Rows
     *
     * @param unknown_type $query_id
     * @return int|boolean 
     */
    public function numrows($query_id = 0)
    {
        if (! $query_id)
            $query_id = $this->query_id;
        
        if ($query_id)
            return mysql_num_rows($query_id);
        else
            return false;
    }
    
    /**
     * get Number of affected rows
     * @return int|boolean 
     */
    public function affectedrows()
    {
        if ($this->link_id)
            return mysql_affected_rows($this->link_id);
        else
            return false;
    }
    
    /**
     * Obtiene los datos de un campo
     *
     * @param string $field
     * @param int $rownum
     * @param int $query_id
     * @return array
     */
    public function fetchfield($rownum = -1, $query_id = 0)
    {
        if (! $query_id)
            $query_id = $this->query_id;
        
        if ($query_id)
        {
            return mysql_fetch_field($query_id, $rownum);
        
        } else
            return false;
    }

}


