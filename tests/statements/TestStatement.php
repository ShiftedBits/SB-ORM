<?php

use framework\orm\statements\Statement;

class TestStatement extends Statement
{

    public function passthrough($sql)
    {
        $this->_sql[] = $sql;
    }

    public function addParameters($params)
    {
        $this->_parameters[] = $params;
    }

}
