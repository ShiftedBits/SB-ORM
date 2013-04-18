<?php

class InnerJoinSource implements Source
{

    private $_table;
    private $_statements;
    private $_operator;

    public function __construct($table, $columnOne, $columnTwo)
    {
        $this->_table = $table;
        $this->_statements = array($columnOne, $columnTwo);
        $this->_operator = "=";
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function setOperator($op)
    {
        $this->_operator = $op;
    }

    public function render()
    {
        $sql = "INNER JOIN `" . $this->_table . "` ON ";
        $sql .= "`" . $this->_statements[0] . "` ";
        $sql .= $this->_operator . " ";
        $sql .= "`" . $this->_statements[1] . "` ";
        return $sql;
    }

    public function parameters()
    {
        return array();
    }

}
