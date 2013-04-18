<?php

class InsertSource implements Source
{

    private $_data;
    private $_table;

    public function __construct($table)
    {
        $this->_table = $table;
        $this->_data  = array();
    }

    public function addData()
    {
        $this->_data[] = func_get_args();
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function render()
    {
        $placeholders = array();
        foreach ($this->_data as $row) {
            $places         = array_fill(0, count($row), "?");
            $placeholders[] = implode(", ", $places);
        }
        $placeholders = implode("), (", $placeholders);
        return "VALUES (" . $placeholders . ") ";
    }

    public function parameters()
    {
        $params = array();
        foreach ($this->_data as $row) {
            foreach ($row as $item) {
                $params[] = array(
                    'type'  => TableMock::getType(gettype($item)),
                    'value' => $item
                );
            }
        }
        return $params;
    }

}
