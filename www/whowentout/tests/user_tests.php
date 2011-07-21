<?php

class User_Tests extends TestGroup
{
  
  function setup() {
    $this->clear_database();
  }
  
  function teardown() {
  }
  
  function test_create_user() {
    $user = create_user('1204337494'); //Alex Webb
    
    $this->assert_equal($user->full_name, 'Alex Webb');
    $this->assert_equal($user->gender, 'M');
    
    $this->assert_true( $user === user($user->id) );
    
    $user->delete();
  }
  
  function test_destroy_user() {
    $user = create_user('8100231'); //Dan Berenholtz
    $user_id = $user->id;
    
    $this->assert_true( $user === user($user_id) );
    
    destroy_user($user_id);
    
    $this->assert_equal(user($user_id), NULL);
    $this->assert_equal($user->exists(), FALSE);
  }
  
  function test_update_user() {
    $user = create_user('8100231'); //Dan Berenholtz
    $this->assert_equal($user->full_name, 'Dan Berenholtz');
    
//    $this->assert_equal($user->hometown, 'Fresh Meadows, NY');
    
    $user->hometown = 'Los Angeles, CA';
    $user->save();
    $this->assert_equal(user($user->id)->hometown, 'Los Angeles, CA');
  }
  
}
