<?php

class DistinctFilterTest extends PHPUnit_Framework_TestCase
{

    private $_filter;

    public function setUp()
    {
        $this->_filter = new DistinctFilter();
    }

    public function tearDown()
    {
        unset($this->_filter);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("DistinctFilter", $this->_filter);
    }

    public function testRender()
    {
        $render = $this->_filter->render();
        $test = "DISTINCT ";
        $this->assertEquals($test, $render);
    }

}
