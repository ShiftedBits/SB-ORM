<?php

class InnerJoinSourceTest extends PHPUnit_Framework_TestCase
{
    private $_source;
    public function setUp()
    {
        $this->_source = new InnerJoinSource("tst_read_read", "one", "two");
    }

    public function tearDown()
    {
        unset($this->_source);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("InnerJoinSource", $this->_source);
    }

    public function testRender()
    {
        $render = $this->_source->render();
        $test = "INNER JOIN `tst_read_read` ON `one` = `two` ";
        $this->assertEquals($test, $render);
    }

    public function testSetOperator()
    {
        $this->_source->setOperator(">");
        $render = $this->_source->render();
        $test = "INNER JOIN `tst_read_read` ON `one` > `two` ";
        $this->assertEquals($test, $render);
    }

}
