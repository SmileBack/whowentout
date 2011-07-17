<?php

/**
 * @return XUser
 */
function get_random_user($party_id) {
  $party = party($party_id);
  $query = "SELECT id, first_name, last_name FROM users
              WHERE gender = 'F'
              ORDER BY RAND()
              LIMIT 1";
  $rows = ci()->db->query($query)->result();
  return user($rows[0]->id);
}

function create_test_users($college_id = NULL) {
  $data = array (
    0 => 
    array (
      'facebook_id' => '100001150127674',
      'first_name' => 'Robert',
      'last_name' => 'Roose',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2011',
      'email' => 'robert@gwu.edu',
      'gender' => 'M',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1990-10-24',
      'pic_x' => '38',
      'pic_y' => '20',
      'pic_width' => '105',
      'pic_height' => '140',
    ),
    1 => 
    array (
      'facebook_id' => '1243620029',
      'first_name' => 'Clara',
      'last_name' => 'Scheinmann',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'clara@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1991-01-31',
      'pic_x' => '20',
      'pic_y' => '20',
      'pic_width' => '140',
      'pic_height' => '187',
    ),
    2 => 
    array (
      'facebook_id' => '1479330106',
      'first_name' => 'Natalie',
      'last_name' => 'Epelman',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2012',
      'email' => 'natalie@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1990-05-16',
      'pic_x' => '17',
      'pic_y' => '20',
      'pic_width' => '147',
      'pic_height' => '196',
    ),
    3 => 
    array (
      'facebook_id' => '1067760090',
      'first_name' => 'Marissa',
      'last_name' => 'Ostroff',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'marissa@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1990-12-09',
      'pic_x' => '20',
      'pic_y' => '20',
      'pic_width' => '140',
      'pic_height' => '187',
    ),
    4 => 
    array (
      'facebook_id' => '1204337494',
      'first_name' => 'Alex',
      'last_name' => 'Webb',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2012',
      'email' => 'alex@gwu.edu',
      'gender' => 'M',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '0000-00-00',
      'pic_x' => '59',
      'pic_y' => '20',
      'pic_width' => '83',
      'pic_height' => '110',
    ),
    5 => 
    array (
      'facebook_id' => '704222664',
      'first_name' => 'Leon',
      'last_name' => 'Harari',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2012',
      'email' => 'leon@gwu.edu',
      'gender' => 'M',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '0000-00-00',
      'pic_x' => '60',
      'pic_y' => '20',
      'pic_width' => '60',
      'pic_height' => '80',
    ),
    6 => 
    array (
      'facebook_id' => '760370505',
      'first_name' => 'Jonny',
      'last_name' => 'Cohen',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2012',
      'email' => 'johnny@gwu.edu',
      'gender' => 'M',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '0000-00-00',
      'pic_x' => '36',
      'pic_y' => '20',
      'pic_width' => '108',
      'pic_height' => '144',
    ),
    7 => 
    array (
      'facebook_id' => '719185695',
      'first_name' => 'Cassie',
      'last_name' => 'Scheinmann',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'cassie@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1991-03-05',
      'pic_x' => '42',
      'pic_y' => '20',
      'pic_width' => '95',
      'pic_height' => '127',
    ),
    8 => 
    array (
      'facebook_id' => '1099920067',
      'first_name' => 'Erica ',
      'last_name' => 'Obersi',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'erica@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1990-04-30',
      'pic_x' => '56',
      'pic_y' => '20',
      'pic_width' => '68',
      'pic_height' => '91',
    ),
    9 => 
    array (
      'facebook_id' => '1682940070',
      'first_name' => 'Ava',
      'last_name' => 'Rubin',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'ava@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1991-01-09',
      'pic_x' => '32',
      'pic_y' => '20',
      'pic_width' => '116',
      'pic_height' => '155',
    ),
    10 => 
    array (
      'facebook_id' => '1067760099',
      'first_name' => 'Anna ',
      'last_name' => 'Lepkoski',
      'hometown' => 'Topeka, KS',
      'college_id' => '1',
      'grad_year' => '2013',
      'email' => 'anna@gwu.edu',
      'gender' => 'F',
      'registration_time' => NULL,
      'last_edit' => NULL,
      'date_of_birth' => '1991-03-02',
      'pic_x' => '35',
      'pic_y' => '20',
      'pic_width' => '110',
      'pic_height' => '146',
    )
  );
  
  foreach ($data as $user_data) {
    if (!$college_id)
      $user_data['college_id'] = $college_id;
    
    XUser::create($user_data);
  }
  
}

function checkout_user($user, $party) {
  $user = user($user);
  $party = party($party);
  
  if ( ! $party || ! $user)
    return FALSE;
  
  ci()->db->delete('party_attendees', array('party_id' => $party->id));
  ci()->db->delete('smiles', array('party_id' => $party->id, 'sender_id' => $user->id));
  ci()->db->delete('smiles', array('party_id' => $party->id, 'receiver_id' => $user->id));
  
  return TRUE;
}
