<?php

abstract class Complex extends Statement
{

    protected $_filters;
    protected $_sources;
    protected $_clauses;
    protected $_destinations;

    public function __construct(Database $d)
    {
        parent::__construct($d);
        $this->reset();
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


    protected function _addParameters($parameters)
    {
        foreach ($parameters as $p) {
            $this->_currentParameters[] = $p;
        }
    }

    public function finish()
    {
        $this->_sql[]        = $this->render();
        $this->_parameters[] = $this->_currentParameters;
        return $this->run();
    }

    public function reset()
    {
        $this->_filters = array();
        $this->_sources = array();
        $this->_destinations = array();
        $this->_clauses = array();
        $this->_currentParameters = array();
    }
    public abstract function render();
}
