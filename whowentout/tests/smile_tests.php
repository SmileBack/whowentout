<?php

class Smile_Tests extends TestGroup
{
  
  function setup() {
    $this->clear_database();
    $this->college = create_college('GWU', '111');
    $this->place = $this->college->add_place('Sig Chi');
    $this->party = $this->college->add_party('2011-10-01', $this->place->id);
    
    create_test_users($this->college->id);
  }
  
  function test_can_smile() {
    $college = $this->college;
    $party = $this->party;
    
    $time = new DateTime('2011-10-02', $college->timezone );
    $time->setTime(12 + 5, 0, 0);
    set_fake_time($time);
    
    $guy = user(array('first_name' => 'Alex'));
    $girl = user(array('first_name' => 'Clara'));
    $other_girl = user(array('first_name' => 'Ava'));
    
    // day after party, so both should be able to checkin
    $this->assert_true( $guy->can_checkin($party) );
    $this->assert_true( $other_girl->can_checkin($party) );
    
    $guy->checkin($party);
    $other_girl->checkin($party);
    
    // smiling at a girl who hasn't checked into a party
    $this->assert_true( ! $guy->can_smile_at($girl, $party) );
    $this->assert_equal($guy->reason(), REASON_RECEIVER_NOT_IN_PARTY);
    
    $girl->checkin($party);
    $this->assert_true( $guy->can_smile_at($girl, $party) );
    $this->assert_true( $girl->can_smile_at($guy, $party) );
    $this->assert_equal($guy->smiles_received($party), 0);
    $this->assert_equal($girl->smiles_received($party), 0);
    
    // make sure smile count is correct
    $this->assert_equal($guy->smiles_left($party), 3);
    $this->assert_equal($girl->smiles_left($party), 3);
    
    $guy->smile_at($girl, $party);
    $matches = $guy->matches($party);
    // girl hasn't smiled back to there are no matches
    $this->assert_true(empty($matches));
    $this->assert_equal($girl->smiles_received($party), 1);
    
    $this->assert_equal($guy->smiles_left($party), 2);
    
    // check that a match occurs
    $girl->smile_at($guy, $party);
    $matches = $guy->matches($party);
    $this->assert_equal($matches[0], $girl);
    $this->assert_equal($guy->smiles_received($party), 1);
    
    $matches = $girl->matches($party);
    $this->assert_equal($matches[0], $guy);
    
    // another random person smlies at the guy.. make the counts update properly
    $other_girl->smile_at($guy, $party);
    $this->assert_equal($guy->smiles_received($party), 2);
    $this->assert_equal($other_girl->smiles_received($party), 0);
    
    // no more matches because guy didn't smile back
    $this->assert_equal( count($guy->matches($party)), 1 );
  }
  
}