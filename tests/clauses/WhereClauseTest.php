<?php

use framework\orm\clauses\WhereClause;

class WhereClauseTest extends PHPUnit_Framework_TestCase
{
    private $_clause;
    public function setUp()
    {
        $this->_clause = new WhereClause("one", 1);
    }

    public function tearDown()
    {
        unset($this->_clause);
    }

    public function testRender()
    {
        $render = $this->_clause->render();
        $test = "WHERE `one` = ? ";
        $this->assertEquals($test, $render);
    }

    public function testIntParameters()
    {
        $this->_clause->set("two", 3, "<");
        $render = $this->_clause->render();
        $parameters = $this->_clause->parameters();
        $test =  "WHERE `two` < ? ";
        $this->assertEquals($test, $render);
        $test = array(
            array(
                'type' => PDO::PARAM_INT,
                'value' => 3
            )
        );
        $this->assertEquals($test, $parameters);
    }

    public function testStrParameters()
    {
        $this->_clause->set("two", "three", "NOT");
        $render = $this->_clause->render();
        $test = "WHERE `two` NOT ? ";
        $this->assertEquals($test, $render);
        $parameters = $this->_clause->parameters();
        $test =  array(
            array(
                'type' => PDO::PARAM_STR,
                'value' => "three"
            )
        );
        $this->assertEquals($test, $parameters);
    }

}
