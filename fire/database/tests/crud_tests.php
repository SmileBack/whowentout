<?php

class Crud_Tests extends TestGroup
{

    /**
     * @var Database
     */
    private $db;

    function setup()
    {
        $this->db = factory()->build('test_database');

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

    function test_create_row_with_time()
    {
        $this->db->table('data')->create_column('my_date', array('type' => 'time'));
        $date = new DateTime('2011-12-02', new DateTimeZone('UTC'));
        $row = $this->db->table('data')->create_row(array(
                                                         'name' => 'woo a date name',
                                                         'my_date' => $date,
                                                    ));

        $same_date = clone $date;
        $this->assert_equal($row->my_date, $same_date);
    }

}
