<?php

class Select extends Complex
{

    public function render()
    {
        $sql = "SELECT ";
        if (array_key_exists(0, $this->_filters)) {
            foreach ($this->_filters as $f) {
                $sql .= $f->render();
            }
        } else {
            $sql .= "* ";
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

}
