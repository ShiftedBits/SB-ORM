<?php

class TestStatement extends Statement
{
    //This class does nothing except for allow for the testing of the public
    //functions of the Query super-class.

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function passthrough($sql)
    {
        $this->_sql[] = $sql;
        $this->_parameters[] = array();
    }
}
