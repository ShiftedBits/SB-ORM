<?php

include "TestStatement.php";

class StatementTest extends PHPUnit_Framework_TestCase
{

    private $_query;

    public function setUp()
    {
        $db           = new Database();
        $db->init($GLOBALS['settings']['database']);
        $this->_query = new TestStatement($db);
    }

    public function tearDown()
    {
        unset($this->_query);
    }

    public function testInstance()
    {
        $this->assertInstanceOf("TestStatement", $this->_query);
    }

    public function testBadSql()
    {
        $this->_query->passthrough("SELECT * FROM bad_table");
        try {
            $rows = $this->_query->run();
        } catch (PDOException $pdoe) {
            $this->assertInstanceOf("PDOException", $pdoe);
        }
    }

    public function testParameterize()
    {
        $this->_query->addTable('tst_tmoc_table_mock');
        $array = array();
        $array[0] = array('tmoc_id' => 1);
        $array[1] = array('tmoc_string' => "");
        $array[2]     = array('tmoc_blob' => "blob");
        $test       = $this->_query->parameterize($array);

        $params = array(
            0 => array(
                'type'  => PDO::PARAM_INT,
                'value' => 1
            ),
            1       => array(
                'type'  => PDO::PARAM_STR,
                'value' => ""
            ),
            2       => array(
                'type'  => PDO::PARAM_LOB,
                'value' => "blob"
            )
        );

        $this->assertEquals($params, $test);
    }

}
