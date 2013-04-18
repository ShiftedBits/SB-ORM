<?php

class InsertSource implements Source
{

    private $_table;

    public function __construct($table)
    {
        $this->_table = $table;
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function render()
    {
        return "INTO " . $this->_table . " ";
    }

}
