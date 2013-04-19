<?php

abstract class Complex extends Statement
{

    private $_filters;
    private $_sources;
    private $_clauses;
    private $_destinations;

    public function __construct(Database $d)
    {
        parent::__construct($d);
        $this->_filters = array();
        $this->_sources = array();
        $this->_destinations = array();
        $this->_clauses = array();
    }

    public function addFilter(Filter $f)
    {
        $this->_filters[] = $f;
        return $this;
    }

    public function addSource(Source $s)
    {
        $this->_sources[] = $s;
        $this->addTable($s->getTableName());
        return $this;
    }

    public function addClause(Clause $c)
    {
        $this->_clauses[] = $c;
        return $this;
    }

    public function addDestination(Destination $d)
    {
        $this->_destinations[] = $d;
        return $this;
    }


    private function _addParameters($parameters)
    {
        foreach ($parameters as $p) {
            $this->_currentParameters[] = $p;
        }
    }

    private function _begin()
    {
        $this->_filters = array();
        $this->_sources = array();
        $this->_destinations = array();
        $this->_clauses = array();
    }

    public function finish()
    {
        $this->_sql[]        = $this->render();
        $this->_parameters[] = $this->parameters();
        return $this->run();
    }

    public abstract function render();
    public abstract function parameters();
}
