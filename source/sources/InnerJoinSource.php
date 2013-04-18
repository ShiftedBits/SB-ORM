<?php

class InnerJoinSource implements Source
{

    private $_statements;
    private $_operator;

    public function __construct($sOne, $sTwo, $op = "=")
    {
        $this->_statements = array($sOne, $sTwo);
        $this->_operator = $op;
    }

    public function render()
    {
        $sql = "INNER JOIN ON";
        $sql .= $this->_statements[0];
        $sql .= " " . $this->_operator . " ";
        $sql .= $this->_statements[1];
        $sql .= " ";
        return $sql;
    }

}
