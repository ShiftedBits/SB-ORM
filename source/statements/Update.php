<?php

class Update extends Complex
{

    private $_data;
    private $_values;

    public function __construct(Database $db)
    {
        parent::__construct($db);
        $this->_data = array();
        $this->_values = array();
    }

    public function addExpression($column, $value, $operator = "=" )
    {
        $this->_data[$column] = $operator;
        $this->_values[] = $value;
    }

    public function render()
    {
        $sql = "UPDATE ";
        foreach ($this->_destinations as $d) {
            $sql .= $d->render();
        }
        $sql .= "SET ";
        foreach ($this->_data as $column => $op) {
            $sql .= "$c $op ?";
        }
        foreach ($this->_clauses as $c) {
            $sql .= $c->render();
        }
        return $sql;
    }

    public function parameters()
    {
        $params = array();
        foreach ($this->_values as $value) {
            $type = TableMock::getType(gettype($value));
            if ($type == PDO::PARAM_STR AND length($value) > 255) {
                $type     = PDO::PARAM_BLOB;
            }
            $params[] = array(
                "type"  => $type,
                "value" => $value
            );
        }
        return $params;
    }

}
