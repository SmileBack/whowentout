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
	
	function get_party_attendees($party_id, $user) {
		$party_attendees = $this->db
			->select('user_id, party_attendees.party_id, first_name, last_name, college_name, grad_year, profile_pic, gender, date_of_birth')
			->where('party_attendees.party_id', $party_id)
			->where('gender', $user->gender == 'M' ? 'F' : 'M')
			->join('users', 'party_attendees.user_id = users.id')
			->join('colleges', 'users.college_id = colleges.id')
			->get('party_attendees')->result();
		
		foreach ($party_attendees as &$attendee) {
			$attendee->was_smiled_at = $this->_get_was_smiled_at($user->id, $attendee->user_id, $party_id);
		}
		return $party_attendees;
		
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
	
	function _get_was_smiled_at($user_id, $attendee_id, $party_id) {
		return $this->db
		->from('smiles')
		->where('sender_id', $user_id)
		->where('receiver_id', $attendee_id)
		->where('party_id', $party_id)
		->count_all_results() == 1;
	}

}