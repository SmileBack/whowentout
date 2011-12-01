<?php

class Schema_Tests extends TestGroup
{

    /**
     * @var Database
     */
    private $db;

    /**
     * @var Database
     */
    private $db2;

    private function create_database_connection()
    {
        return new Database(array(
                                 'host' => 'localhost',
                                 'username' => 'root',
                                 'password' => 'root',
                                 'database' => 'fire_test',
                            ));
    }

    function setup()
    {
        $this->db = $this->create_database_connection();

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

        $this->assert_true(!$db->table_exists('test_table'));

        $db->create_table('test_table', array(
                                             'id' => array('type' => 'id'),
                                             'foo' => array('type' => 'string'),
                                        ));

        $this->assert_true($db->table_exists('test_table'));

        $table = $db->table('test_table');
        $this->assert_equal($table->name(), 'test_table');
    }

    function test_table_persistance()
    {
        $db = $this->db;
        $db->create_table('uncached_table', array(
                                                 'id' => array('type' => 'id'),
                                            ));

        $db2 = $this->create_database_connection();
        $table = $db2->table('uncached_table', 'table persists between database sessions');
        $this->assert_true($table != null);
        $this->assert_true($db2->table_exists('uncached_table'));
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

    function test_rename_column()
    {
        $db = $this->db;

        $table = $db->create_table('rename_column_table', array(
                                                               'id' => array('type' => 'id'),
                                                               'orange' => array('type' => 'string'),
                                                          ));

        $column = $table->column('orange');
        $this->assert_equal($column->name(), 'orange');

        $table->rename_column('orange', 'apple');

        $this->assert_equal($table->column('orange'), null);
        $this->assert_equal($table->column('apple'), $column, 'new column name refers to same object');
        $this->assert_equal($column->name(), 'apple', 'name has changed for object');
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

    function test_create_single_column_index()
    {
        $db = $this->db;
        $table = $db->create_table('create_index_table', array(
                                                              'id' => array('type' => 'id'),
                                                              'indexed_column' => array('type' => 'string'),
                                                         ));

        $this->assert_true(!$table->has_index('indexed_column'), 'no index before you create one');

        $table->create_index('indexed_column');
        $this->assert_true($table->has_index('indexed_column'), 'index one you create one');

        $table->destroy_index('indexed_column');
        $this->assert_true(!$table->has_index('indexed_column'), 'no index after you destroy it');
    }

    function test_create_multi_column_index()
    {
        $db = $this->db;
        $table = $db->create_table('create_multi_column_index_table', array(
                                                                           'id' => array('type' => 'id'),
                                                                           'indexed_column_1' => array('type' => 'string'),
                                                                           'indexed_column_2' => array('type' => 'string'),
                                                                      ));

        $this->assert_true(!$table->has_index('indexed_column_1', 'indexed_column_2'), 'no index before you create one');

        $table->create_index('indexed_column_1', 'indexed_column_2');
        $this->assert_true($table->has_index('indexed_column_1', 'indexed_column_2'), 'index present after you create it');
        $this->assert_true($table->has_index('indexed_column_2', 'indexed_column_1'), 'column order doesnt matter');

        $table->destroy_index('indexed_column_1', 'indexed_column_2');
        $this->assert_true(!$table->has_index('indexed_column_1', 'indexed_column_2'), 'no index after you destroy it');
    }

    function test_destroy_column_with_foreign_key()
    {
        $this->assert_true(FALSE, 'not yet implemented');
    }

    function test_destroy_column_with_index()
    {
        $this->assert_true(FALSE, 'not yet implemented');
    }

    function test_create_foreign_key()
    {
        $db = $this->db;

        $users_table = $db->create_table('users', array(
                                                       'id' => array('type' => 'id'),
                                                       'name' => array('type' => 'string'),
                                                  ));

        $admins_table = $db->create_table('admins', array(
                                                         'id' => array('type' => 'id'),
                                                         'user_id' => array('type' => 'integer'),
                                                    ));
        
        $admins_table->create_foreign_key('user_id', 'users', 'id');

        $this->assert_true(FALSE, 'not yet implemented');
    }

}
