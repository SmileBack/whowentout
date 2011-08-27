<?php

class Core_Tests extends TestGroup
{
  
  function setup() {
    $this->clear_database();
    $this->college = create_college('GWU', '111');
  }
  
  function test_post_function() {
    $_POST['abc'] = 'def';
    $this->assert_equal(post('abc'), 'def');
    unset($_POST['abc']);
    $this->assert_equal(post('abc'), NULL);
  }
  
  function test_options() {
    $this->assert_equal(get_option('crazzzyoption'), NULL, 'nonexistant option');
    
    set_option('abc', 'def');
    set_option('yay', '123');
    $this->assert_equal(get_option('abc'), 'def', 'option that exists');
    $this->assert_equal(get_option('yay'), '123', 'set 2 options');
    
    unset_option('abc');
    $this->assert_equal(get_option('abc'), NULL, 'unset option');
  }
  
  function test_time_comparison() {
    $dt1 = new DateTime('2011-05-13');
    $dt2 = new DateTime('2011-05-24');
    $this->assert_true($dt2 > $dt1);
  }
  
  function test_fake_time() {
    unset_fake_time();
    $this->assert_equal(current_time(), new DateTime());
    $this->assert_true(!time_is_faked());
    set_fake_time( new DateTime('2011-10-01') );
    $this->assert_true(time_is_faked());
    
    $this->assert_true(current_time() != new DateTime());
    $this->assert_equal(current_time()->format('Y-m-d'), '2011-10-01');
    
    unset_fake_time();
    $this->assert_true(current_time() == actual_time());
    
    $real_time = new DateTime();
    set_fake_time($real_time->modify('+1 hour'));
    $diff_in_seconds = current_time()->format('U') - actual_time()->format('U');
    $this->assert_equal($diff_in_seconds, 3600);
    unset_fake_time();
    
    set_fake_time_of_day(5, 12, 29);
    $this->assert_equal(date_format(current_time(), 'H:i:s'), '05:12:29');
  }
  
  function test_college_fake_time() {
    unset_fake_time();
    $this->assert_equal(college()->current_time(), new DateTime());
    $this->assert_true(!time_is_faked());
    
    set_fake_time( new DateTime('2011-10-01') );
    $this->assert_true(time_is_faked());
    $this->assert_equal(current_time()->format('Y-m-d'), '2011-10-01');
    
    unset_fake_time();
    $this->assert_true(college()->current_time() == actual_time());
    
    $real_time = new DateTime();
    set_fake_time($real_time->modify('+1 hour'));
    $diff_in_seconds = college()->current_time()->format('U') - actual_time()->format('U');
    $this->assert_equal($diff_in_seconds, 3600);
    unset_fake_time();
  }
  
  function test_state_abbreviation() {
    $this->assert_equal(get_state_abbreviation('New York'), 'NY');
    $this->assert_equal(get_state_abbreviation('California'), 'CA');
  }
  
}
