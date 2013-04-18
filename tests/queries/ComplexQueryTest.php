<?php

class ComplexQueryTest extends PHPUnit_Framework_TestCase
{

    private $_query;

    public function setUp()
    {
        $db           = new Database();
        $db->init($GLOBALS['settings']['database']);
        $this->_query = new ComplexQuery($db);
    }

    public function tearDown()
    {
        unset($this->_query);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("ComplexQuery", $this->_query);
    }

    public function testBeginGet()
    {
        $dump = $this->_query->beginGet()->dump();
        $test = array(
            'SELECT * ',
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testBeginPost()
    {
        $dump = $this->_query->beginPost()->dump();
        $test = array(
            'INSERT * ',
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testBeginPut()
    {
        $dump = $this->_query->beginPut()->dump();
        $test = array(
            'UPDATE * ',
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testBeginDelete()
    {
        $dump = $this->_query->beginDelete()->dump();
        $test = array(
            'DELETE * ',
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddFilter()
    {
        $dump = $this->_query->beginGet()->addFilter(new ColumnFilter("one", "two"))->dump();
        $test = array(
            'SELECT `one`, `two` ',
            array()
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddMultipleFilters()
    {
        $dump = $this->_query->beginGet()
            ->addFilter(new DistinctFilter())
            ->addFilter(new ColumnFilter("one", "two"))
            ->dump();
        $test = array(
            'SELECT DISTINCT `one`, `two` ',
            array()
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddSource()
    {
        $dump = $this->_query->beginGet()
            ->addSource(new TableSource("tst_read_read"))
            ->dump();
        $test = array(
            'SELECT * FROM `tst_read_read` ',
            array()
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddMultipleSources()
    {
        $source = new InnerJoinSource("tst_writ_write");
        $source->addColumns("read_one", "writ_one");
        $dump   = $this->_query->beginGet()
            ->addSource(new TableSource("tst_read_read"))
            ->addSource($source)
            ->dump();
        $test   = array(
            "SELECT * FROM `tst_read_read` INNER JOIN `tst_writ_write` ON `read_one` = `writ_one` ",
            array()
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddClause()
    {
        $dump = $this->_query->beginGet()
            ->addClause(new LimitClause(10))
            ->dump();
        $test = array(
            "SELECT * LIMIT 0, 10 ",
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testAddMultipleClauses()
    {
        $dump = $this->_query->beginGet()
            ->addClause(new LimitClause(10))
            ->addClause(new OrderClause("one"))
            ->dump();
        $test = array(
            "SELECT * LIMIT 0, 10 ORDER BY `one` DESC ",
            array(
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testAllTogetherNow()
    {
        $dump = $this->_query->beginGet()
            ->addFilter(new ColumnFilter("read_two"))
            ->addSource(new TableSource("tst_read_read"))
            ->addClause(new WhereClause("read_one", 1))
            ->dump();
        $test = array(
            "SELECT `read_two` FROM `tst_read_read` WHERE `read_one` = ? ",
            array(
                array(
                    'type'  => PDO::PARAM_INT,
                    'value' => 1
                )
            )
        );
        $this->assertEquals($test, $dump);
    }

    public function testFinish()
    {
        $rows = $this->_query->beginGet()
            ->addFilter(new ColumnFilter("read_two"))
            ->addSource(new TableSource("tst_read_read"))
            ->addClause(new WhereClause("read_one", 1))
            ->finish();
        $test = array(
            'read_two' => 2
        );
        $this->assertEquals($test, $rows);
    }

}
