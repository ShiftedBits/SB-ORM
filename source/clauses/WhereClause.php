<?php

class WhereClause implements Clause
{
    public function __construct($column = "", $value = 1, $op = "=")
    {
        $this->set($column, $value, $op);
    }

    public function set($column, $value, $op = "=")
    {
        $this->_column = $column;
        $this->_value = $value;
        switch(gettype($value)){
            case 'integer':
            case 'double':
                $this->_valueType = PDO::PARAM_INT;
                break;
            default:
                $this->_valueType = PDO::PARAM_STR;
                break;
        }
        $this->_operator = $op;
    }
    public function render()
    {
        return "WHERE `$this->_column` $this->_operator ? ";
    }

    public function parameters()
    {
        return array(
            array(
                'type' => $this->_valueType,
                'value' => $this->_value
            )
        );
    }
}
