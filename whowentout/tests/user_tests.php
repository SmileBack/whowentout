<?php

class User_Tests extends TestGroup
{
  
  function setup() {
    $this->clear_database();
    $this->gwu = create_college('GWU', '16777270', '108727889151725', TRUE);
  }
  
  function teardown() {
  }
  
  function test_create_user() {
    $user = create_user('1204337494'); //Alex Webb
    
    $this->assert_equal($user->full_name, 'Alex Webb');
    $this->assert_equal($user->gender, 'M');
    
    $this->assert_true( $user === user($user->id) );
    
    $user->delete();
    
    destroy_user($user);
  }
  
  function test_destroy_user() {
    $user = create_user('8100231'); //Dan Berenholtz
    $user_id = $user->id;
    
    $this->assert_true( $user === user($user_id) );
    
    destroy_user($user_id);
    
    $this->assert_equal(user($user_id), NULL);
    $this->assert_equal($user->exists(), FALSE);
  }
  
  function test_match_college() {
    $venkat = create_user('776200121');
    
    $this->assert_equal($venkat->college, $this->gwu);
    
    destroy_user($venkat);
  }
  
  function test_is_missing_info() {
    $user = create_user('776200121'); //Venkat Dinavahi
    $this->assert_true( ! $user->is_missing_info() );
    $user->hometown = '';
    $this->assert_true( $user->is_missing_info() );
    $user->hometown = 'Fresh Meadows, NY';
    $user->grad_year = '';
    $this->assert_true( $user->is_missing_info() );
    $user->grad_year = '2014';
    $this->assert_true( ! $user->is_missing_info() );
    destroy_user($user);
  }
  
  function test_update_user() {
    $user = create_user('8100231'); //Dan Berenholtz
    $this->assert_equal($user->full_name, 'Dan Berenholtz');
    $this->assert_equal($user->hometown, 'Fresh Meadows, NY');
    
    $user->hometown = 'Los Angeles, CA';
    $user->save();
    $this->assert_equal(user($user->id)->hometown, 'Los Angeles, CA');
  }
  
}
