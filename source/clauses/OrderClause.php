<?php

class OrderClause implements Clause
{

    public function __construct($direction = "DESC")
    {
        $this->_columns = array();
        $this->_direction = $direction;
    }

    public function addColumns()
    {
        $args = func_get_args();
        if (is_array($args[0])) {
            foreach ($args[0] as $column) {
                $this->_columns[] = $column;
            }
        } else {
            foreach ($args as $column) {
                $this->_columns[] = $column;
            }
        }
    }

    public function addColumn($name)
    {
        $this->_columns[] = $name;
    }

    public function setDirection($direction)
    {
        $this->_direction = $direction;
    }

    public function render()
    {
        return "ORDER BY `" . implode("`, `", $this->_columns) . "` " . $this->_direction . " ";
    }

    public function parameters()
    {
        return NULL;
    }

}
