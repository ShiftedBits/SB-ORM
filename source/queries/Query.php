<?php

abstract class Query
{

    protected $_sql;
    protected $_parameters;
    private $_autorun;
    private $_connection;
    private $_tables;

    public function __construct(Database $connection)
    {
        $this->_sql = array();
        $this->_parameters = array();
        $this->_autorun    = TRUE;
        $this->_connection = $connection;
        $this->_tables     = array();
    }

    public function run($override = FALSE)
    {

        $returns = array();
        $error = FALSE;
        try {
            $this->_connection->beginTransaction();
            if ($override === TRUE OR $this->_autorun === TRUE) {
                foreach ($this->_sql as $index => $statement) {
                    $parameters = $this->_parameters[$index];
                    $returns[]  = $this->_connection->run($statement, $parameters);
                }
            }
        } catch (PDOException $pdoe) {
            $error = TRUE;
            $this->_connection->rollback();
        }
        if ($error === FALSE) {
            $this->_connection->commit();
        }
        $this->_parameters = array();
        $this->_sql = array();
        if (sizeof($returns) === 1) {
            if (sizeof($returns[0]) === 1) {
                return $returns[0][0];
            } else {
                return $returns[0];
            }
        } else {
            return $returns;
        }
    }

    public function addTable($name)
    {
        $tm              = new TableMock($name, $this->_connection);
        $this->_tables[] = $tm;
    }

    private function _getColumnType($name)
    {
        $type = null;
        foreach ($this->_tables as $table) {
            if ($table->isColumn($name)) {
                $type = $table->getColumnType($name);
            }
        }
        return $type;
    }

    /**
     * Auto-parameterizes data to allow for usage prepared sql statements.
     * @param Array $data Array of data to be parameterized.
     * @return Array Array allowing prepared sql statements to run with vars.
     */
    public function parameterize($data)
    {
        $ret = array();
        foreach ($data as $key => $value) {
            $column    = array_keys($value)[0];
            $val       = $value[$column];
            $type      = $this->_getColumnType($column);
            $ret[$key] = array(
                'type'  => $type,
                'value' => $val
            );
        }
        return $ret;
    }

}
