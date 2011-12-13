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
        $this->clear_database($this->db);
        
        $this->db->create_table('data', array(
                                             'id' => array('type' => 'id'),
                                             'name' => array('type' => 'string'),
                                        ));

        $this->db->create_table('data_no_pk', array(
                                                'name' => array('type' => 'string'),
                                              ));
    }

    function clear_database(Database $database)
    {
        $database->execute('SET foreign_key_checks = 0');
        foreach ($database->list_table_names() as $table_name) {
            $database->destroy_table($table_name);
        }
        $database->execute('SET foreign_key_checks = 1');
    }

    function teardown()
    {
    }

    function test_insert()
    {
        $table = $this->db->table('data');
        $this->assert_equal($table->row(1), null, 'row doesnt exist');

        $this->assert_equal($table->count(), 0, 'zero rows in db');

        $row = $table->create_row(array('name' => 'ven'));
        $this->assert_equal($table->row($row->id), $row, 'same object is referenced');
        $this->assert_equal($row->name, 'ven', 'row data was properly saved');

        $this->assert_equal($table->count(), 1, '1 row in db');

        $second_row = $table->create_row(array('name' => 'bob'));
        $this->assert_equal($table->count(), 2, '2 rows in db');

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

    function test_create_or_update_row()
    {
        $table = $this->db->table('data');

        $row_a = $table->create_row(array('name' => 'row a'));

        $updated_row = $table->create_or_update_row(array('id' => $row_a->id, 'name' => 'woo'));
        $this->assert_equal($row_a->name, 'woo', 'row was successfully updated');
        $this->assert_true($updated_row == $row_a, 'correct row was updated');

        $row_b = $table->create_or_update_row(array(
                                                  'name' => 'row b',
                                              ));

        $this->assert_equal($row_b->name, 'row b', 'row was successfully created');
        
        $this->assert_true($row_a->id != $row_b->id);
    }

//    function test_insert_no_pk()
//    {
//        $table = $this->db->table('data_no_pk');
//        $row = $table->create_row(array(
//                               'name' => 'woo',
//                           ));
//
//        $this->assert_true($table->where('name', 'woo')->first() != null);
//    }

}
