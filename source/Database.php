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
 * Database Class
 * This class provides nothing more than a simplified extension to PDO, to
 * automatically  prepare statements and their variables, then run them all.
 * Also provides simeple timing (using the included Timing framework) and query
 * counts.
 *
 * @package    Frmeawork
 * @subpackage Database
 * @author     Mike Sherwood <coolhand2@gmail.com>
 * @license    http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
 * @version    Release: 1.0
 * @link       http://www.shiftedbits.net/
 */
class Database
{

    /**
     * The Database Handler Resource
     * @var Resource
     */
    private $_dbh;

    /**
     * The Query Counter
     * @var Int
     */
    private $_counter;

    /**
     * Creates the Database Handler Resource using the appropriate details.
     */
    public function __construct($settings)
    {
        $this->_counter = 0;
        $host           = $settings['host'];
        $dbname         = $settings['dbname'];
        $user           = $settings['user'];
        $password       = $settings['pass'];
        $dsn            = "mysql:host=$host;dbname=$dbname";
        $this->_dbh     = new PDO($dsn, $user, $password);
    }

    /**
     * Destroys the PDO resource, thusly also closing the connection.
     */
    public function __destruct()
    {
        $this->_dbh = NULL;
    }

    /**
     * Runs any query, and returns the result.
     *
     * @param string $sql The SQL query we're to get results from.
     * @param array $parameters The parameters (if any) to the statement.
     * @return array The rows returned (if any).
     */
    public function fetch($sql, $parameters = array())
    {
        $this->_incrementCounter();
        $statement = $this->_prepare($sql, $parameters);
        $statement->execute();
        $results   = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Runs any query, not worrying about any return results.
     *
     * @param type $sql The SQL query to be run
     * @param type $parameters The parameters (if any) to the statement
     */
    public function run($sql, $parameters = array())
    {
        $this->_incrementCounter();
        $statement = $this->_prepare($sql, $parameters);
        $statement->execute();
    }

    /**
     * Prepare's all the variables for the execution of the statement.
     *
     * @param type $sql The SQL query we're preparing.
     * @param type $parameters
     * @return type
     */
    private function _prepare($sql, $parameters)
    {
        $statement = $this->_dbh->prepare($sql);
        foreach ($parameters as $k => $v) {
            $statement->bindParam($k + 1, $v['value'], $v['type']);
        }
        return $statement;
    }

    /**
     * Increments the count of SQL queries ran this session.
     */
    private function _incrementCounter()
    {
        $this->_counter++;
    }

    /**
     * Returns the amount of SQL queries run this session.
     *
     * @return int the amount of SQL queries run this session.
     */
    public function getCounter()
    {
        return $this->_counter;
    }

}
