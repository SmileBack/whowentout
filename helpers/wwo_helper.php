<?php

function get_timer() {
	return '2h 52 min';
}

function load_view($view_name, $data = array()) {
  $CI =& get_instance(); 
  return $CI->load->view($view_name, $data, TRUE);
}

function get_age($dob) {
	if (is_string($dob)) {
	  $dob = strtotime($dob);
	}
	$now = time();
	
	$years_elapsed = date('Y', $now) - date('Y', $dob);
	$birthday_happened = date('z', $now) >= date('z', $dob);
	return $birthday_happened ? $years_elapsed : $years_elapsed - 1;
	return $years_elapsed;
}




/*
function get_user() {
	return array(
		'first_name'=> 'Dan', 
		'last_initial'=> 'B', 
		'age'=> 23, 
		'college'=> 'Stanford', 
		'grad_year'=> "'12",
		'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Dan\'s picture', 'class'=> 'DanPic'),
		'parties_attended' => get_parties_attended(),
	);
}

function get_parties_attended($user_id) {
	return array(
	  	array(
		'place'=> 'McFaddens', 
		'place_admin'=> 'Alex Webb', 
		'date'=> 'Saturday, September 17th', 
		'smiles_received'=> '65 girls', 
		'smiles_remaining'=> '3 smiles', 
		'matches'=> 'Jennifer L.'
		),
	 	array(
		'place'=> 'Sigma Chi', 
		'place_admin'=> 'Alex Webb', 
		'date'=> 'Saturday, September 16th', 
		'smiles_received'=> '7 girl', 
		'smiles_remaining'=> '0 smiles', 
		'matches'=> 'Clara S.'
		),
		array(
		'place'=> 'Sky Bar', 
		'place_admin'=> 'Alex Webb', 
		'date'=> 'Saturday, September 15th', 
		'smiles_received'=> '0 girls', 
		'smiles_remaining'=> '0 smiles', 
		'matches'=> 'Marissa O.'
		),
	);
}

function get_places($school_id) {
	return array('McFaddens', 'Sigma Chi', 'Sky Bar');
}

function get_attendees($party_id) {
	return array(
		array(
		'name'=> 'Clara S.',
		'age'=> 20,
		'school'=> 'GWU',
		'grad_year'=> "'13",
		'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Clara\'s picture', 'class'=> 'ClaraPic'),
		'parties_attended'=> get_parties_attended(),
		'mutual_friends'=> 8,
		),
		array(
		'name'=> 'Natalie E.',
		'age'=> 21,
		'school'=> 'GWU',
		'grad_year'=> "'12",
		'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Natalie\'s picture', 'class'=> 'NataliePic'),
		'parties_attended'=> get_parties_attended(),
		'mutual_friends'=> 16,
		),
		array(
		'name'=> 'Marissa O.',
		'age'=> 20,
		'school'=> 'GWU',
		'grad_year'=> "'13",
		'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Marissa\'s picture', 'class'=> 'MarissaPic'),
		'parties_attended'=> get_parties_attended(),
		'mutual_friends'=> 12,
		),
	);
}
*/