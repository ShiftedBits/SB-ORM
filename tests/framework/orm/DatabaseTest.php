<?php

class DatabaseTest extends PHPUnit_Framework_TestCase
{

    private $_database;

    public function setUp()
    {
        $this->_database = new Database($GLOBALS['settings']['database']);
    }

    public function tearDown()
    {
        $this->_database->run("truncate table tst_writ_write");
        unset($this->_database);
    }

    public function testBadCreate()
    {
        //This just causes code coverage of the exception catching to be covered
        $settings = array();
        $settings['host']   = 'localhost';
        $settings['dbname'] = 'database';
        $settings['user']   = 'user';
        $settings['pass']   = 'password';
        try {
            $db = new Database($settings);
        } catch (PDOException $e) {
            $this->assertInstanceOf("PDOException", $e);
        }
    }

    /**
     * @depends testBadCreate
     */
    public function testInstance()
    {
        $this->assertInstanceOf("Database", $this->_database);
    }

    /**
     * @depends testInstance
     */
    public function testReadSimple()
    {
        $rows = $this->_database->fetch("select * from tst_read_read");
        $test = array(
            0 => array(
                'read_one'   => 1,
                'read_two'   => 2,
                'read_three' => 3
            ),
            1            => array(
                'read_one'   => 3,
                'read_two'   => 2,
                'read_three' => 1
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testWriteSimple()
    {
        $this->_database->run("insert into tst_writ_write values(1, 2, 3)");
        $rows = $this->_database->fetch("select * from tst_writ_write");
        $test = array(
            0 => array(
                'writ_one'   => 1,
                'writ_two'   => 2,
                'writ_three' => 3
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testReadSimple
     */
    public function testReadPlaceholder()
    {
        $sql    = "select * from tst_read_read where read_one = ?";
        $params = array(array('value' => 3, 'type'  => PDO::PARAM_INT));
        $rows   = $this->_database->fetch($sql, $params);
        $test   = array(
            0 => array(
                'read_one'   => 3,
                'read_two'   => 2,
                'read_three' => 1
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testWriteSimple
     */
    public function testWritePlaceholder()
    {
        $sql    = "insert into tst_writ_write (writ_one, writ_two, writ_three) values(?, ?, ?)";
        $params = array(
            array('value' => 3, 'type'  => PDO::PARAM_INT),
            array('value' => 2, 'type'  => PDO::PARAM_INT),
            array('value' => 1, 'type'  => PDO::PARAM_INT),
        );
        $this->_database->run($sql, $params);
        $rows   = $this->_database->fetch("select * from tst_writ_write");
        $test   = array(
            0 => array(
                'writ_one'   => '3',
                'writ_two'   => '2',
                'writ_three' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testBadCreate
     * @depends testInstance
     * @depends testReadSimple
     * @depends testWriteSimple
     * @depends testReadPlaceholder
     * @depends testWritePlaceholder
     */
    public function testCounter()
    {
        $count = $this->_database->getCounter();
        $this->assertEquals(0, $count);
    }

}
