<?php

class OrderClauseTest extends PHPUnit_Framework_TestCase
{
    private $_clause;
    public function setUp()
    {
        $this->_clause = new OrderClause("one");
    }

    public function tearDown()
    {
        unset($this->_clause);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("OrderClause", $this->_clause);
    }

    /**
     * @depends testInstance
     */
    public function testSingleColumn()
    {
        $render = $this->_clause->render();
        $test = "ORDER BY `one` DESC ";
        $this->assertEquals($test, $render);
    }

    /**
     * @depends testSingleColumn
     */
    public function testMultiColumn()
    {
        $this->_clause->addColumn("two");
        $render = $this->_clause->render();
        $test = "ORDER BY `one`, `two` DESC ";
        $this->assertEquals($test, $render);
    }

    /**
     * @depends testMultiColumn
     */
    public function testDirectionChange()
    {
        $this->_clause->setDirection("ASC");
        $render = $this->_clause->render();
        $test = "ORDER BY `one` ASC ";
        $this->assertEquals($test, $render);
    }

    /**
     * @depends testDirectionChange
     */
    public function testAddMultipleColumns()
    {
        $this->_clause->addColumns("two", "three");
        $render = $this->_clause->render();
        $test = "ORDER BY `one`, `two`, `three` DESC ";
        $this->assertEquals($test, $render);
    }

    /**
     * @depends testAddMultipleColumns
     */
    public function testAddMultipleColumnsAsArray()
    {
        $this->_clause->addColumns(array("two", "three"));
        $render = $this->_clause->render();
        $test = "ORDER BY `one`, `two`, `three` DESC ";
        $this->assertEquals($test, $render);
    }
}
