<?php

class User_Permissions_Tests extends TestGroup
{
  
  function setup() {
    $this->clear_database();
    $this->college = create_college('GWU', '111');
    $this->outside_college = create_college('NYU', '222');
    $this->place = $this->college->add_place('Sig Chi');
    $this->party = $this->college->add_party('2011-10-01', $this->place->id);
    
    create_test_users($this->college->id);
    
    $this->user = user(array('first_name' => 'Alex')); //Alex Webb
    
    $this->outsider = user(array('first_name' => 'Jonny'));
    $this->outsider->college_id = $this->outside_college->college_id;
    $this->outsider->save();
  }
  
  function test_doors_are_closed() {
    $time = actual_time(TRUE);
    
    $time->setTime(5, 30, 0);
    set_fake_time($time);
    $this->assert_true(doors_are_open());
    
    $time->setTime(12 + 10, 59, 59);
    set_fake_time($time);
    $this->assert_true(doors_are_open());
    
    $time->setTime(12 + 11, 0, 0);
    set_fake_time($time);
    $this->assert_true(doors_are_closed());
    
    $time->setTime(12 + 11, 30, 0);
    set_fake_time($time);
    $this->assert_equal(doors_are_closed(), TRUE);
    
    $time->setTime(12 + 11, 0, 0);
    set_fake_time($time);
    $this->assert_equal(doors_are_closed(), TRUE);
    
    $time->setTime(0, 30, 0);
    set_fake_time($time);
    $this->assert_equal(doors_are_closed(), TRUE);
  }
  
  function test_can_checkin() {
    $user = $this->user;
    $party = $this->party;
    
    $time = new DateTime('2011-10-02', college()->timezone);
    $time->setTime(12 + 10, 30, 0);
    set_fake_time($time);
    $this->assert_equal($user->can_checkin($party->id), TRUE);
    
    $time->setTime(1, 0, 0);
    set_fake_time($time);
    $this->assert_equal($user->can_checkin($party->id), TRUE);
    
    $time->setTime(0, 59, 59);
    set_fake_time($time);
    $this->assert_equal($user->can_checkin($party->id), FALSE);
  }
  
  function test_cant_checkin_after_close() {
    $user = $this->user;
    $party = $this->party;
    
    $time = new DateTime('2011-10-02', college()->timezone);
    $time->setTime(12 + 11, 30, 0);
    set_fake_time($time);
    
    $can_checkin = $user->can_checkin($party->id);
    $this->assert_equal($can_checkin, FALSE);
    $this->assert_equal($user->reason(), REASON_DOORS_HAVE_CLOSED);
  }
  
  function test_outsider_cant_checkin() {
    $user = $this->outsider;
    $party = $this->party;
    
    $time = new DateTime('2011-10-02', college()->timezone);
    $time->setTime(12 + 10, 30, 0);
    set_fake_time($time);
    
    $can_checkin = $user->can_checkin($party->id);
    $this->assert_equal($can_checkin, FALSE);
    $this->assert_equal($user->reason(), REASON_NOT_IN_COLLEGE);
  }
  
  function test_checkin_with_nonexistant_party() {
    $user = $this->user;
    
    $time = new DateTime('2011-10-02', college()->timezone);
    $time->setTime(12 + 10, 30, 0);
    set_fake_time($time);
    
    $can_checkin = $user->can_checkin(32);
    $this->assert_equal($can_checkin, FALSE);
    $this->assert_equal($user->reason(), REASON_PARTY_DOESNT_EXIST);
  }
  
}
