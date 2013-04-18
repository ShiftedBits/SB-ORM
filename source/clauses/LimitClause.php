<?php

class LimitClause implements Clause
{

    public function __construct()
    {
        $args = func_get_args();
        if (count($args) === 1) {
            $this->_offset = 0;
            $this->_limit  = $args[0];
        } else {
            $this->_offset = $args[0];
            $this->_limit  = $args[1];
        }
    }

    public function render()
    {
        return "LIMIT $this->_offset, $this->_limit ";
    }

    public function parameters()
    {
        return array(
        );
    }

}
