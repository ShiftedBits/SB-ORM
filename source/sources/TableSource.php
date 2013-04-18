<?php

class TableSource implements Source
{

    private $_table;

    public function __construct($table)
    {
        $this->_table = $table;
    }

    public function render()
    {
        return "FROM " . $this->_table;
    }

}
