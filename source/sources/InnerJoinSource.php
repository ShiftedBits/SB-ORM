<?php

class InnerJoinSource implements Source
{

    private $_table;
    private $_statements;
    private $_operator;

    public function __construct($table)
    {
        $this->_table = $table;
    }

    public function addColumns($one, $two, $op = "=")
    {
        $this->_statements = array($one, $two);
        $this->_operator = $op;
        return $this;
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function render()
    {
        $sql = "INNER JOIN `" . $this->_table . "` ON ";
        $sql .= "`" . $this->_statements[0] . "` ";
        $sql .= $this->_operator . " ";
        $sql .= "`" . $this->_statements[1] . "` ";
        return $sql;
    }

}
