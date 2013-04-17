<?php

/**
 * Project: ShiftedBitsFramework
 * File:    SimpleQuery.php
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
 * SimpleQuery Class
 * This class provides a simple interface to perform the most basic of SQL
 * queries with any applicable data. Providing most basic CRUD abilities, in the
 * flavour of RESTful names.
 *
 * @package    Framework
 * @subpackage Database
 * @author     Mike Sherwood <coolhand2@gmail.com>
 * @license    http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
 * @link       http://www.shiftedbits.net/
 */
class SimpleQuery
{

    /**
     * The TableMock object representing the table/view we're handling data from
     * @var TableMock
     */
    private $_mock;

    /**
     * Database connection resource.
     * @var Resource
     */
    private $_connection;

    /**
     * The name of the table we're querying
     * @var String
     */
    private $_table;

    /**
     * The primary key for the table we're querying.
     * @var String
     */
    private $_key;

    /**
     * Creates the object correctly.
     * @param TableMock $tableMock Mock instance of the table we're manipulating
     */
    public function __construct(TableMock $tableMock, Database $connection)
    {
        $this->_mock       = $tableMock;
        $this->_connection = $connection;
        $this->_table      = $this->_mock->getTableName();
        $this->_key        = $this->_mock->getPrimaryKey();
    }

    /**
     * Abstract function allowing the get of multiple different ways. One by
     * a single id, one by multiple ids, and one by a column-value pair.
     * @return Array Array of row(s) that we've retrieved.
     */
    public function get()
    {
        $args = func_get_args();
        if (count($args) === 1) {
            if (is_array($args[0])) {
                return $this->_getByIds($args[0]);
            } else {
                return $this->_getById($args[0]);
            }
        } else {
            if (is_string($args[0])) {
                return $this->_getByColumn($args[0], $args[1]);
            } else {
                return $this->_getByIds($args);
            }
        }
    }

    /**
     * Gets a single row by the given id.
     * @param int $id Id of the row we're picking.
     * @return Array Array of the row we're retrieving.
     */
    private function _getById($id)
    {
        $sql = "SELECT * FROM `%s` WHERE `%s` = ?";
        $sql = sprintf($sql, $this->_table, $this->_key);

        $parameters = array(array($this->_key => $id));
        $parameters = $this->_mock->parameterize($parameters);

        return $this->_connection->fetch($sql, $parameters);
    }

    /**
     * Gets multiple rows by their given ids.
     * @param Array $ids Ids of all the rows we want.
     * @return Array Array of the row we're retrieving.
     */
    public function _getByIds($ids)
    {
        $sql    = "SELECT * FROM `%s` WHERE `%s` IN (%s)";
        $array  = array_fill(0, count($ids), "?");
        $places = implode(",", $array);
        $sql    = sprintf($sql, $this->_table, $this->_key, $places);

        $parameters = array();
        foreach ($ids as $id) {
            $parameters[] = array($this->_key => $id);
        }
        $parameters = $this->_mock->parameterize($parameters);

        return $this->_connection->fetch($sql, $parameters);
    }

    /**
     * Returns any rows that match the column-value pair given.
     * @param String Column name.
     * @param mixed Value given.
     * @return Array Array of the row(s) we're retrieving.
     */
    public function _getByColumn($column, $value)
    {
        $sql        = "SELECT * FROM `%s` WHERE `%s` = ?";
        $sql        = sprintf($sql, $this->_table, $column);
        $parameters = array(array($column     => $value));
        $parameters = $this->_mock->parameterize($parameters);
        return $this->_connection->fetch($sql, $parameters);
    }

    /**
     * Runs a general select query to select all data within a table, but only
     * returning the passed column names.
     * @param var_arg The names of the columns to be selected.
     * @return Array Row data from the selection query.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM `%s`";
        $sql = sprintf($sql, $this->_table);

        return $this->_connection->fetch($sql);
    }

    /**
     * Takes an array of data and pushes it into the appropriate table.
     * @param array $data A key map of input data to be put into the database.
     */
    public function post($data)
    {
        $columns = array_keys($data);
        $columns = implode("`, `", $columns);

        $placeholders = array_fill(0, count($data), "?");
        $placeholders = implode(", ", $placeholders);

        $values = array();
        foreach ($data as $k => $v) {
            $values[] = array($k => $v);
        }

        $sql = "INSERT INTO `%s` (`%s`) VALUES (%s)";
        $sql = sprintf($sql, $this->_table, $columns, $placeholders);

        $parameters = $this->_mock->parameterize($values);

        $this->_connection->run($sql, $parameters);
    }

    /**
     * Takes many arrays and creates multiple insert statements out of them,
     * then runs them all at the same time.
     * @param Array $data Key->Data mapped arrays to be put into the database.
     */
    public function postMany($data)
    {
        $places = array();
        $columns = array_keys($data[0]);
        foreach ($data as $a) {
            $placeholders = array_fill(0, count($a), "?");
            $placeholders = implode(", ", $placeholders);
            $places[]     = $placeholders;
        }

        $columns = implode("`, `", $columns);
        $places  = implode("), (", $places);

        $sql = "INSERT INTO `%s` (`%s`) VALUES (%s)";
        $sql = sprintf($sql, $this->_table, $columns, $places);

        $parameters = $this->_mock->parameterize($data);

        $this->_connection->run($sql, $parameters);
    }

    /**
     * Updates a set row within the database.
     * @param int $id Id of the row we're updating.
     * @param array $data column->value map of the data we're updating with.
     */
    public function put($id, $data)
    {
        $column     = array_keys($data)[0];
        $sql        = "UPDATE `%s` SET `%s` = ? WHERE `%s` = ?";
        $sql        = sprintf(
            $sql, $this->_table, $column, $this->_key, $id
        );
        $parameters = array(array($column => $data[$column]), array($this->_key => $id));
        $parameters = $this->_mock->parameterize($parameters);
        $this->_connection->run($sql, $parameters);
    }

    /**
     * Updates multiple rows within the database.
     * @param array $ids All the ids that are to be updated.
     * @param array $data column->value map of the data we're updating.
     */
    public function putMany($ids, $data)
    {
        $sql          = "UPDATE `%s` SET `%s` = ? WHERE `%s` IN (%s)";
        $column       = array_keys($data)[0];
        $placeholders = implode(", ", array_fill(0, count($ids), "?"));
        $sql          = sprintf($sql, $this->_table, $column, $this->_key, $placeholders);
        $parameters   = array(
            array($column => $data[$column])
        );
        foreach ($ids as $id) {
            $parameters[] = array($this->_key => $id);
        }
        $parameters = $this->_mock->parameterize($parameters);
        $this->_connection->run($sql, $parameters);
    }

    /**
     * Deletes the given id from the table.
     * @param int $id Id of the row we're going to delete.
     */
    public function delete($id)
    {
        $sql        = "DELETE FROM `%s` WHERE `%s` = ?";
        $sql        = sprintf($sql, $this->_table, $this->_key);
        $parameters = array(array($this->_key => $id));
        $parameters = $this->_mock->parameterize($parameters);
        $this->_connection->run($sql, $parameters);
    }

    /**
     * Deletes many rows from the table using the given ids.
     * @param array $ids List of ids that we're deleting.
     */
    public function deleteMany($ids)
    {
        $placeholders = array_fill(0, count($ids), "?");
        $placeholders = implode(", ", $placeholders);

        $sql        = "DELETE FROM `%s` WHERE `%s` IN (%s)";
        $sql        = sprintf($sql, $this->_table, $this->_key, $placeholders);
        $parameters = array();
        foreach ($ids as $id) {
            $parameters[] = array($this->_key => $id);
        }
        $parameters = $this->_mock->parameterize($parameters);
        $this->_connection->run($sql, $parameters);
    }

}
