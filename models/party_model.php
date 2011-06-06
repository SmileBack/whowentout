<?php

class Party_model extends CI_Model {
	
	function get_party($id, $user_id) {
		$party= $this->db
			->select('parties.id, party_date, place_name, first_name AS admin_first_name, last_name AS admin_last_name')
			->where('parties.id', $id)
			->join('places', 'parties.place_id = places.id')
			->join('users', 'parties.admin = users.id')
			->get('parties')->row();
			
		$party->matches = $this->_get_matches($user_id, $party->id);
		$party->smiles_received = $this->_get_smiles_received($user_id, $party->id);
		$party->smiles_remaining = $this->_get_smiles_remaining($user_id, $party->id);
			
		return $party;
	}
	
	function get_party_attendees($id) {
		return $this->db
			->select('party_attendees.party_id, first_name, last_name, college_name, grad_year, profile_pic, gender, date_of_birth')
			->where('party_attendees.party_id', $id)
			->where('gender', 'F')
			->join('users', 'party_attendees.user_id = users.id')
			->join('colleges', 'users.college_id = colleges.id')
			->get('party_attendees')->result();
	}
	
	function get_parties_attended($user_id) {
		$parties_attended= $this->db
			->select('user_id, parties.id, party_date, place_name')
			->from('party_attendees')
			->where('user_id', $user_id)
			->join('parties', 'party_attendees.party_id = parties.id')
			->join('places', 'parties.place_id = places.id')
			->get()->result();
		
		foreach ($parties_attended as &$party) {
			$party->matches = $this->_get_matches($user_id, $party->id);
			$party->smiles_received = $this->_get_smiles_received($user_id, $party->id);
			$party->smiles_remaining = $this->_get_smiles_remaining($user_id, $party->id);
		}
		
		return $parties_attended;
	}
	
	function _get_matches($sender_id, $party_id) { 
		return $this->db
		->query("SELECT first_name, last_name FROM users WHERE id
		IN (SELECT receiver_id FROM smiles WHERE (sender_id = $sender_id) AND (party_id = $party_id))
		AND id IN (SELECT sender_id FROM smiles WHERE receiver_id = 1)")
		->result();
	}
	
	function _get_smiles_received($user_id, $party_id) {
		return $this->db
		->from('smiles')
		->where('receiver_id', $user_id)
		->where('party_id', $party_id)
		->count_all_results();
	}
	
	function _get_smiles_remaining($user_id, $party_id) {
		$smiles_used = $this->db
		->from('smiles')
		->where('sender_id', $user_id)
		->where('party_id', $party_id)
		->count_all_results();
		return (3 - $smiles_used);
	}
	
}








		
/*	
	
	function get_parties_attended() {
		return array(
			 array(
			'party_id'=> 1,
			//'place'=> 'McFaddens', 
			'place_admin'=> 'Alex Webb', 
			//'date'=> 'Saturday, September 17th', 
			'smiles_received'=> '4 girls', 
			'smiles_remaining'=> '3 smiles', 
			'matches'=> 'Jennifer L.'
			),
		 	array(
			'party_id'=> 2,
			//'place'=> 'Sigma Chi', 
			'place_admin'=> 'Joe Shmo', 
			//'date'=> 'Friday, September 16th', 
			'smiles_received'=> '3 girls', 
			'smiles_remaining'=> '0 smiles', 
			'matches'=> 'Clara S.'
			),
			array(
			'party_id'=> 3,
			//'place'=> 'Lambda Chi', 
			'place_admin'=> 'Jonny Cohen', 
			//'date'=> 'Thursday, September 15th', 
			'smiles_received'=> '0 girls', 
			'smiles_remaining'=> '0 smiles', 
			'matches'=> 'Marissa O.'
			),
		);
	}
*/

/*
function get_attendees() {
	return array(
		array(
		'name'=> 'Clara S.',
		'age'=> 20,
		'college'=> 'GWU',
		'grad_year'=> "'13",
		'image'=> array('src'=> 'clara_pic.jpg', 'alt'=> 'Clara\'s picture', 'class'=> 'ClaraPic'),
		//'parties_attended'=> $this->get_parties_attended(),
		'mutual_friends'=> 8,
		),
		array(
		'name'=> 'Natalie E.',
		'age'=> 21,
		'college'=> 'GWU',
		'grad_year'=> "'12",
		'image'=> array('src'=> 'natalie_pic.jpg', 'alt'=> 'Natalie\'s picture', 'class'=> 'NataliePic'),
		//'parties_attended'=> $this->get_parties_attended(),
		'mutual_friends'=> 16,
		),
		array(
		'name'=> 'Marissa O.',
		'age'=> 20,
		'college'=> 'GWU',
		'grad_year'=> "'13",
		'image'=> array('src'=> 'marissa_pic.jpg', 'alt'=> 'Marissa\'s picture', 'class'=> 'MarissaPic'),
		//'parties_attended'=> $this->get_parties_attended(),
		'mutual_friends'=> 14,
		),
	);
*/