<?php

class College_model extends CI_Model {

//This function grabs names of places where parties occur at a college
	function get_places() {
		$places= $this->db->select('id, place_name')
		->where('college_id', 1)
		->get('places')->result();
		
		$new_places = array();
		foreach ($places as $place) {
			$new_places[$place->id] = $place->place_name;
		}
		
		return $new_places;
	}


}
