<?php

class SimpleQueryTest extends PHPUnit_Framework_TestCase
{
    private $_sq;
    private $_db;
    public function setUp()
    {
        $this->_db = new Database();
        $this->_db->init($GLOBALS['settings']['database']);
        $tm = new TableMock('tst_sque_simple_query', $this->_db);
        $this->_sq = new SimpleQuery($tm, $this->_db);
        $this->_sq->post(array('sque_id' => 1, 'sque_value' => 1));
        $this->_sq->post(array('sque_id' => 2, 'sque_value' => 2));
    }

    public function tearDown()
    {
        $this->_db->run("TRUNCATE TABLE `tst_sque_simple_query`");
        unset($this->_sq);
        unset($this->_db);

    }

    public function testInstance()
    {
        $this->assertInstanceOf('SimpleQuery', $this->_sq);
    }

    /**
     * @depends testInstance
     */
    public function testGetById()
    {
        $rows = $this->_sq->get(1);
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '1',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testGetByIdsVarArg()
    {
        $rows = $this->_sq->get(1, 2);
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '1',
                'sque_active' => '1',
                'sque_locked' => '1',
            ),
            1 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1',
            ),
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testGetByIdsArray()
    {
        $rows = $this->_sq->get(array(1, 2));
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '1',
                'sque_active' => '1',
                'sque_locked' => '1',
            ),
            1 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1',
            ),
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testGetByColumn()
    {
        $rows = $this->_sq->get('sque_value', 2);
        $test = array(
            0 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testGetAll()
    {
        $rows = $this->_sq->getAll();
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '1',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            1 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1',
            ),
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testPost()
    {
        $data = array(
            'sque_value' => 3
        );
        $this->_sq->post($data);
        $rows = $this->_sq->get(3);
        $test = array(
            0 => array(
                'sque_id' => '3',
                'sque_value' => '3',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testGetAll
     */
    public function testPostMany()
    {
        $data = array(
            array('sque_value' => 3),
            array('sque_value' => 4),
            array('sque_value' => 5)
        );
        $this->_sq->postMany($data);
        $rows = $this->_sq->getAll();
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '1',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            1 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            2 => array(
                'sque_id' => '3',
                'sque_value' => '3',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            3 => array(
                'sque_id' => '4',
                'sque_value' => '4',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            4 => array(
                'sque_id' => '5',
                'sque_value' => '5',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     */
    public function testPut()
    {
        $data = array('sque_value' => 2);
        $this->_sq->put(1, $data);
        $rows = $this->_sq->get(1);
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testPostMany
     * @depends testGetAll
     */
    public function testPutMany()
    {
        $postData = array(
            array('sque_value' => 3),
            array('sque_value' => 4),
            array('sque_value' => 5)
        );
        $putData = array('sque_value' => 2);
        $ids = array(1, 2, 3, 4);
        $this->_sq->postMany($postData);
        $this->_sq->putMany($ids, $putData);
        $rows = $this->_sq->getAll();
        $test = array(
            0 => array(
                'sque_id' => '1',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            1 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            2 => array(
                'sque_id' => '3',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            3 => array(
                'sque_id' => '4',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
            4 => array(
                'sque_id' => '5',
                'sque_value' => '5',
                'sque_active' => '1',
                'sque_locked' => '1'
            )
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testGetAll
     */
    public function testDelete()
    {
        $this->_sq->delete(1);
        $rows = $this->_sq->getAll();
        $test = array(
            0 => array(
                'sque_id' => '2',
                'sque_value' => '2',
                'sque_active' => '1',
                'sque_locked' => '1'
            ),
        );
        $this->assertEquals($test, $rows);
    }

    /**
     * @depends testInstance
     * @depends testPost
     * @depends testGetAll
     */
    public function testDeleteMany()
    {
        $this->_sq->deleteMany(array(1, 2));
        $rows = $this->_sq->getAll();
        $test = array();
        $this->assertEquals($test, $rows);
    }

}