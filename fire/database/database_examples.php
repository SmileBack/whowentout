<?php

//connect to database
$db = new Database(array(
                        'host' => 'localhost',
                        'username' => 'root',
                        'password' => '123',
                   ));

//create a table
$db->create_table('facebook_events', array(
                                          'id' => array(
                                              'type' => 'id',
                                          ),
                                          'name' => array(
                                              'type' => 'string',
                                          ),
                                          'start' => array(
                                              'type' => 'time',
                                          ),
                                          'end' => array(
                                              'type' => 'time',
                                          ),
                                     ));

//drop a table
$db->table('facebook_events')->drop();

//rename table
$db->table('facebook_events')->rename('events');

//add column
$db->table('facebook_events')->add_column('version', array(
                                                          'type' => 'integer',
                                                     ));

//rename column
$db->table('facebook_events')->column('version')->rename();

//delete column
$db->table('facebook_events')->column('version')->remove();

//add index
$db->table('facebook_events')->add_index('start', 'end');
$db->table('facebook_events')->add_index(array('start', 'end'));
$db->table('facebook_events')->add_unique_index('start', 'end');

//remove index
$db->table('facebook_events')->remove_index('start', 'end');

