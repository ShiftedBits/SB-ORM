<?php

abstract class Statement
{

    protected $_sql;
    protected $_parameters;
    private $_autorun;
    private $_connection;
    private $_tables;
    private $_returnType;

    const RETURN_FULL     = 1;
    const RETURN_ARRAY    = 2;
    const RETURN_FILTERED = 3;

    public function __construct(Database $connection)
    {
        $this->_sql = array();
        $this->_parameters = array();
        $this->_autorun    = TRUE;
        $this->_connection = $connection;
        $this->_tables     = array();
        $this->_returnType = self::RETURN_FILTERED;
    }

    public function run($override = FALSE)
    {
        $returns = array();
        try {
            $this->_connection->beginTransaction();
            if ($override === TRUE OR $this->_autorun === TRUE) {
                foreach ($this->_sql as $index => $statement) {
                    $parameters = $this->_parameters[$index];
                    $returns[]  = $this->_connection->run($statement, $parameters);
                }
            }
        } catch (PDOException $pdoe) {
            $this->_connection->rollback();
            throw $pdoe;
        }
        $this->_connection->commit();
        $this->_parameters = array();
        $this->_sql = array();
        return $this->_filterReturns($returns);
    }

    public function setReturnType($type)
    {
        $this->_returnType = $type;
        return $this;
    }

    private function _filterReturns($returns)
    {
        if ($this->_returnType == self::RETURN_FULL) {
            return $returns;
        }
        if ($this->_returnType == self::RETURN_ARRAY) {
            if (sizeof($returns) === 1) { //Only one statement returning.
                if (count($returns[0]) === 1) { //Only one row returned.
                    return array_values($returns[0][0]);
                } else { //Return all rows from single statement.
                    return $returns[0];
                }
            } else { //Multiple rows being returned. Return them all.
                return $returns;
            }
        }
        if ($this->_returnType == self::RETURN_FILTERED) {
            if (sizeof($returns) === 1) { //Only one statement returning.
                if (count($returns[0]) === 0) { //No actual results.
                    return $returns[0];
                } else if (count($returns[0]) === 1) { //Only one row returned.
                    if (count($returns[0][0]) === 1) { //Only one cell returned.
                        return array_values($returns[0][0])[0];
                    } else { //More than one cell returned.
                        return $returns[0][0];
                    }
                } else { //Multiple rows being returned.
                    if (count($returns[0][0]) === 1) { //Only one cell per row.
                        $items = array();
                        foreach ($returns[0] as $item) {
                            $items[] = array_values($item)[0];
                        }
                        return $items;
                    } else { //Multiple cells, multiple rows. Return them all.
                        return $returns[0];
                    }
                }
            } else { //Multiple statements being returned. Return them all.
                return $returns;
            }
        }
    }

    public function setOption($flag)
    {
        $this->_options[] = $flag;
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
