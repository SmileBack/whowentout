<?php

class Crud_Tests extends TestGroup
{

    /**
     * @var Database
     */
    private $db;

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

        $this->db->create_table('data', array(
                                             'id' => array('type' => 'id'),
                                             'name' => array('type' => 'string'),
                                        ));
    }

    function teardown()
    {
    }

    function test_insert()
    {
        $table = $this->db->table('data');
        $this->assert_equal($table->row(1), null, 'row doesnt exist');

        $row = $table->create_row(array('name' => 'ven'));
        $this->assert_equal($table->row($row->id), $row, 'same object is referenced');
        $this->assert_equal($row->name, 'ven', 'row data was properly saved');

        $this->assert_true(is_int($row->id));
        $this->assert_true(is_string($row->name));
    }

    function test_update()
    {
        $table = $this->db->table('data');
        $row = $table->create_row(array('name' => 'antelope'));

        $this->assert_equal($row->name, 'antelope');

        $row->name = 'koala';
        $row->save();

        $this->assert_equal($table->row($row->id)->name, 'koala');
    }

    function test_destroy()
    {
        $table = $this->db->table('data');

        $row1 = $table->create_row(array('name' => 'row1'));
        $row2 = $table->create_row(array('name' => 'row2'));

        $row1_id = $row1->id;
        $row2_id = $row2->id;

        $table->destroy_row($row1_id);
        $this->assert_equal($table->row($row1_id), null, 'deleted row is null');
        $this->assert_equal($table->row($row2_id), $row2, 'regular row is still there');
    }

}
