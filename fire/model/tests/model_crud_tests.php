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
    private $people;

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

        $test_models_table = $this->db->create_table('people', array(
                                                                         'id' => array('type' => 'id'),
                                                                         'name' => array('type' => 'string'),
                                                                         'age' => array('type' => 'integer'),
                                                                    ));

        $this->people = new ModelCollection($test_models_table, 'TestPersonModel');
    }

    function teardown()
    {
    }
    
    function test_create()
    {
        $bob = $this->people->create(array(
                                                 'name' => 'bob',
                                                 'age' => 42,
                                            ));

        $joe = $this->people->create(array(
                                              'name' => 'joe',
                                              'age' => 25,
                                          ));

        $this->assert_equal($bob->name, 'bob');
        $this->assert_equal($bob->age, 42);
        $this->assert_true(is_int($bob->age));

        $this->assert_equal($this->people->find($joe->id), $joe);
        $this->assert_equal($this->people->find($bob->id), $bob);
    }

    function test_destroy()
    {
        $temp_person = $this->people->create(array(
                                                 'name' => 'Tempy',
                                                 'age' => 22,
                                             ));
        $temp_person_id = $temp_person->id;

        $this->assert_equal($this->people->find($temp_person_id), $temp_person);

        $this->people->destroy($temp_person->id);
        $this->assert_equal($this->people->find($temp_person_id), null);
    }

}
