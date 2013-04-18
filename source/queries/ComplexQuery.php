<?php

class ComplexQuery extends Query
{

    private $_filters;
    private $_sources;
    private $_clauses;
    private $_destinations;
    protected $_currentSql;
    protected $_currentParameters;

    public function __construct(Database $d)
    {
        parent::__construct($d);
        $this->_currentSql        = "";
        $this->_currentParameters = array();
        $this->_filters = array();
        $this->_sources = array();
        $this->_destinations = array();
        $this->_clauses = array();
    }

    public function beginGet()
    {
        $this->_begin();
        $this->_currentSql .= "SELECT ";
        return $this;
    }

    public function beginPost()
    {
        $this->_begin();
        $this->_currentSql .= "INSERT ";
        return $this;
    }

    public function beginPut()
    {
        $this->_begin();
        $this->_currentSql .= "UPDATE ";
        return $this;
    }

    public function beginDelete()
    {
        $this->_begin();
        $this->_currentSql .= "DELETE ";
        return $this;
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
        $this->_currentSql        = "";
        $this->_currentParameters = array();
        $this->_filters = array();
        $this->_sources = array();
        $this->_destinations = array();
        $this->_clauses = array();
    }

    public function dump()
    {
        $this->_prep();
        return array($this->_currentSql, $this->_currentParameters);
    }

    public function finish()
    {
        $this->_prep();
        $this->_sql[]        = $this->_currentSql;
        $this->_parameters[] = $this->_currentParameters;
        return $this->run();
    }

    private function _prep()
    {
        if (count($this->_filters) > 0) {
            foreach ($this->_filters as $f) {
                $this->_currentSql .= $f->render();
            }
        } else {
            $this->_currentSql .= "* ";
        }
        foreach ($this->_destinations as $d) {
            $this->_currentSql .= $d->render();
        }
        foreach ($this->_sources as $s) {
            $this->_currentSql .= $s->render();
            $this->_addParameters($s->parameters());
        }
        foreach ($this->_clauses as $c) {
            $this->_currentSql .= $c->render();
            $this->_addParameters($c->parameters());
        }
    }
}
