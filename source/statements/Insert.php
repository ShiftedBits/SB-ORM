<?php

class Insert extends Complex
{
    public function render()
    {
        $sql = "INSERT ";
        foreach ($this->_destinations as $d) {
            $sql .= $d->render();
        }
        foreach ($this->_filters as $f) {
            $sql .= $f->render();
        }
        foreach ($this->_sources as $s) {
            $sql .= $s->render();
            $this->_addParameters($s->parameters());
        }
        foreach ($this->_clauses as $c) {
            $sql .= $c->render();
            $this->_addParameters($c->parameters());
        }
        return $sql;
    }

    public function parameters()
    {
        return array();
    }
}
