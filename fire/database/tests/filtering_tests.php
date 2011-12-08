<?php

class Filtering_Tests extends TestGroup
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

        $table = $this->db->create_table('food', array(
                                                      'id' => array('type' => 'id'),
                                                      'name' => array('type' => 'string'),
                                                      'purchased' => array('type' => 'date'),
                                                      'type' => array('type' => 'string'),
                                                 ));
        $table->create_row(array(
                                'name' => 'apple',
                                'purchased' => new DateTime('2011-12-07'),
                                'type' => 'fruit',
                           ));

        $table->create_row(array(
                                'name' => 'carrot',
                                'purchased' => new DateTime('2011-08-23'),
                                'type' => 'vegetable',
                           ));

        $table->create_row(array(
                                'name' => 'orange',
                                'purchased' => new DateTime('2011-12-07'),
                                'type' => 'fruit',
                           ));

        $table->create_row(array(
                                'name' => 'kiwi',
                                'purchased' => new DateTime('2011-12-08'),
                                'type' => 'fruit',
                           ));

        $table->create_row(array(
                               'name' => 'celery',
                               'purchased' => new DateTime('2011-12-07'),
                               'type' => 'vegetable',
                           ));
    }

    function test_basic_where()
    {
        $food = array();
        $set = $this->db->table('food')->where('name', 'orange');
        foreach ($this->db->table('food')->where('name', 'orange') as $id => $orange) {
            $food[] = $orange->name;
        }
        $this->assert_equal($food[0], 'orange');
    }

    function test_date_where()
    {
        $items = array();
        $seventh = new DateTime('2011-12-07');
        foreach ($this->db->table('food')->where('purchased', $seventh) as $id => $item) {
            $items[] = $item->name;
        }
        $this->assert_equal(implode(',', $items), 'apple,orange,celery');
    }

    function test_multi_where()
    {
        $seventh = new DateTime('2011-12-07');
        $item = $this->db->table('food')
                         ->where('purchased', $seventh)
                         ->where('type', 'vegetable')
                         ->first();
        $this->assert_equal($item->name, 'celery');
    }

}
