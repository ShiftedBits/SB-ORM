<?php

class ColumnFilter implements Filter
{

    private $_columns;

    public function __construct()
    {
        $args = func_get_args();

        if (is_array($args[0])) {
            $this->_columns = $args[0];
        } else {
            $this->_columns = $args;
        }
    }

    public function addColumn($name)
    {
        $this->_columns[] = $name;
    }

    public function render()
    {
        return "`" . implode("`, `", $this->_columns) . "` ";
    }

}
