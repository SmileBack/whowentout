<?php

class College_model extends CI_Model {

//This function grabs names of places where parties occur at a college, and flattens the array
	function get_places() {
		$places = $this->db->select('id, place_name')
		->where('college_id', 1)
		->get('places')->result();
		
		$new_places = array();
		foreach ($places as $place) {
			$new_places[$place->id] = $place->place_name;
		}
		
		return $new_places;
	}
	
	function get_party($place_id, $date) {
		$parties = $this->db->from('parties')
	         			->where(array(
	           				'place_id' => $place_id,
	           				'party_date' => date('Y-m-d', $date),
	         			  ))
	         			->get()->result();
	    
		return empty($parties) ? NULL : $parties[0];
	}

}
