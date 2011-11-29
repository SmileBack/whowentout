<?php

class Schema_Tests extends TestGroup
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
    }

    function teardown()
    {
    }

    function test_create_basic_table()
    {
        $db = $this->db;
        $this->assert_equal($db->table('test_table'), null);

        $db->create_table('test_table', array(
                                             'id' => array('type' => 'id'),
                                             'foo' => array('type' => 'string'),
                                        ));

        $table = $db->table('test_table');
        $this->assert_equal($table->name(), 'test_table');
    }

    function test_destroy_table()
    {
        $db = $this->db;

        $db->create_table('test_table_to_destroy', array(
                                                        'id' => array('type' => 'id'),
                                                   ));


        $db->destroy_table('test_table_to_destroy');

        $test_table = $db->table('test_table_to_destroy');
        $this->assert_equal($test_table, null);
    }

    function test_rename_table()
    {
        $db = $this->db;

        $table = $db->create_table('ven', array(
                                               'id' => array('type' => 'id'),
                                          ));

        $this->assert_equal($db->table('ven')->name(), 'ven');
        $this->assert_equal($table, $db->table('ven'), 'tables refer to the same object');

        $db->rename_table('ven', 'vendiddy');

        $this->assert_equal($db->table('ven'), null);
        $this->assert_equal($db->table('vendiddy')->name(), 'vendiddy');
        $this->assert_equal($table->name(), 'vendiddy', 'previously referenced table has updated name');
    }

    function test_create_column()
    {
        $db = $this->db;

        $db->create_table('col_table', array(
                                            'id' => array('type' => 'id'),
                                       ));
        $table = $db->table('col_table');
        $this->assert_equal($table->column('new_col'), null, 'column doesnt exist yet');

        $table->create_column('new_col', array('type' => 'string'));
        $column = $table->column('new_col');
        $this->assert_equal($column->name(), 'new_col');
    }

    function test_destroy_column()
    {
        $db = $this->db;
        $db->create_table('destroy_col_table', array(
                                                    'id' => array('type' => 'id'),
                                                    'temp_col' => array('type' => 'string'),
                                               ));


        $this->assert_equal($db->table('destroy_col_table')->column('temp_col')->name(), 'temp_col');

        $db->table('destroy_col_table')->destroy_column('temp_col');
        $this->assert_equal($db->table('destroy_col_table')->column('temp_col'), null);
    }

    function test_create_index()
    {
        $db = $this->db;
        $db->create_table('create_index_table', array(
                                                    'id' => array('type' => 'id'),
                                                    'indexed_column' => array('type' => 'string'),
                                               ));
        
        $db->table('create_index_table')->create_index('indexed_column');
        $db->table('create_index_table')->destroy_index('indexed_column');
        $db->table('create_index_table')->destroy_index('indexed_column_woo');
    }

}
