<?php

class TableMockTest extends PHPUnit_Framework_TestCase
{

    private $_tableMock;

    public function setUp()
    {
        $db = new Database();
        $db->init($GLOBALS['settings']['database']);
        $this->_tableMock = new TableMock('tst_tmoc_table_mock', $db);
    }

    public function tearDown()
    {
        unset($this->_tableMock);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("TableMock", $this->_tableMock);
    }

    public function testGetTableName()
    {
        $name = $this->_tableMock->getTableName();
        $this->assertEquals('tst_tmoc_table_mock', $name);
    }

    public function testGetPrimaryKey()
    {
        $name = $this->_tableMock->getPrimaryKey();
        $this->assertEquals('tmoc_id', $name);
    }

    public function testIsColumn()
    {
        $test = $this->_tableMock->isColumn('tmoc_id');
        $this->assertEquals(true, $test);

        $test = $this->_tableMock->isColumn('tmoc_id_2');
        $this->assertEquals(false, $test);
    }
}