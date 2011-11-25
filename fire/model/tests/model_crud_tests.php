<?php

class Model_Crud_Tests extends TestGroup
{

    /**
     * @var Database
     */
    private $db;

    /**
     * @var ModelCollection
     */
    private $test_models;

    function setup()
    {
        $this->db = new Database(array(
                                      'host' => 'localhost',
                                      'username' => 'root',
                                      'password' => 'root',
                                      'database' => 'fire_test',
                                 ));

        foreach ($this->db->list_table_names() as $table_name) {
            $this->db->destroy_table($table_name);
        }

        $test_models_table = $this->db->create_table('test_models', array(
                                                                         'id' => array('type' => 'id'),
                                                                         'name' => array('type' => 'string'),
                                                                    ));

        $this->test_models = new ModelCollection($test_models_table);
    }

    function teardown()
    {
    }


    function test_create()
    {
        $model = $this->test_models->create(array(
                                                 'name' => 'bob',
                                            ));
        
    }

}
