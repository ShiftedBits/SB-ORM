<?php

class ColumnFilterTest extends PHPUnit_Framework_TestCase
{

    private $_filter;

    public function setUp()
    {
        $this->_filter = new ColumnFilter("one", "two", "three");
    }

    public function tearDown()
    {
        unset($this->_filter);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("ColumnFilter", $this->_filter);
    }

    public function testRender()
    {
        $render = $this->_filter->render();
        $test   = "`one`, `two`, `three` ";
        $this->assertEquals($test, $render);
    }

    public function testVarArgs()
    {
        $filter = new ColumnFilter(array("one", "two", "three"));
        $render = $filter->render();
        $test   = "`one`, `two`, `three` ";
        $this->assertEquals($test, $render);
    }

    public function testAddColumn()
    {
        $this->_filter->addColumn("four");
        $render = $this->_filter->render();
        $test   = "`one`, `two`, `three`, `four` ";
        $this->assertEquals($test, $render);
    }

}
