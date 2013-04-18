<?php

class QueryTest
{
    public function testParameterize()
    {
        $array = array();
        $array[0] = array('tmoc_id' => 1);
        $array[1] = array('tmoc_string' => "");
        $array[2]     = array('tmoc_blob' => "blob");
        $test       = $this->_tableMock->parameterize($array);

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
