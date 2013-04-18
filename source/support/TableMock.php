<?php

/**
 * Project: ShiftedBitsFramework
 * File:    Database.php
 *
 * PHP version 5.3.4
 *
 * This code is distributed into the public domain. Unless required by appliable
 * law or agreed to in writing, this software is distributed on an "AS IS" BASIS
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * @package    Framework
 * @subpackage Database
 * @author     Mike Sherwood <coolhand2@gmail.com>
 * @license    http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
 * @link       http://www.shiftedbits.net/
 */

/**
 * TableMock Class
 * This class provides a simple interface for another class to analize a named
 * table, and pull out peices of data about it, including column names, and
 * the name of the primary key.
 *
 * @package    Frmeawork
 * @subpackage Database
 * @author     Mike Sherwood <coolhand2@gmail.com>
 * @license    http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
 * @version    Release: 1.0
 * @link       http://www.shiftedbits.net/
 */
class TableMock
{

    /**
     * Holds all the names of the columns of the table
     * @var Array
     */
    private $_columns;

    /**
     * Holds the name of the table.
     * @var String
     */
    private $_name;

    /**
     * Holds the name of the primary key.
     * @var String
     */
    private $_primaryKey;

    /**
     * Holds the database connection to do analyzation with.
     * @var Resource
     */
    private $_database;

    /**
     * Sets table name, and connection resource, then analyzes table.
     *
     * @param String $tableName Name of the table to mock.
     * @param Database $connection Database Connection Resource
     */
    public function __construct($tableName, Database $connection)
    {
        $this->_name     = $tableName;
        $this->_database = $connection;
        $this->_analyzeTable();
    }

    /**
     * Returns the name of the table in this mock object.
     * @return String Name of the mocked table.
     */
    public function getTableName()
    {
        return $this->_name;
    }

    /**
     * Returns the name of the primary key column of the table.
     * @return String Name of the primary key column.
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    public function getColumnType($name)
    {
        return $this->_columns[$name];
    }

    /**
     * Checks to see if the given column name is part of the mocked table.
     * @param String $name Name of the column we're checking.
     * @return Boolean Whether or not the column is part of the table.
     */
    public function isColumn($name)
    {
        return array_key_exists($name, $this->_columns);
    }

    /**
     * Analyzes the table, pulling column names, types, and the name of the
     * column that is the primary key.
     */
    private function _analyzeTable()
    {
        $results = $this->_database->fetch("DESC {$this->_name}");
        foreach ($results as $desc) {
            $this->_columns[$desc['Field']] = self::getType($desc['Type']);
            if ($desc['Key'] == 'PRI') {
                $this->_primaryKey = $desc['Field'];
            }
        }
    }

    /**
     * Analyzes a MySQL Column type and translates it to a PDO type.
     * @param String $type MySQL-returned column type that we're analyzing.
     * @return int PDO Parameter placeholder type.
     */
    public static function getType($type)
    {
        if (preg_match("(int|integer|decimal|float|double|bit)", $type)) {
            return PDO::PARAM_INT;
        } else if (preg_match("(blob|binary)", $type)) {
            return PDO::PARAM_LOB;
        }
        return PDO::PARAM_STR;
    }

}
