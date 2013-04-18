<?php

class LimitClauseTest extends PHPUnit_Framework_TestCase
{

    private $_clauseOne;
    private $_clauseTwo;

    public function setUp()
    {
        $this->_clauseOne = new LimitClause(10);
        $this->_clauseTwo = new LimitClause(5, 15);
    }

    public function tearDown()
    {
        unset($this->_clauseOne);
        unset($this->_clauseTwo);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("LimitClause", $this->_clauseOne);
        $this->assertInstanceOf("LimitClause", $this->_clauseTwo);
    }

    /**
     * @depends testInstance
     */
    public function testRender()
    {
        $render = $this->_clauseOne->render();
        $test = "LIMIT 0, 10 ";
        $this->assertEquals($test, $render);

        $render = $this->_clauseTwo->render();
        $test = "LIMIT 5, 15 ";
        $this->assertEquals($test, $render);
    }

}
