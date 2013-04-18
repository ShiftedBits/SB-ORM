<?php

class InsertDestination implements Destination
{
    private $_table;
    public function __construct($table)
    {
        $this->_table = $table;
    }

    public function render()
    {
        return "INTO `" . $this->_table . "` ";
    }
}
