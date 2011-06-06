<?php

class College_model extends CI_Model {

	function get_places() {
		$places= $this->db
		->where('college_id', 1)
		->get('places')->result();
		return $places;
	}
	
	
	
	
	
/*	function get_place_names() {
	  $places = $this->db->where('college_id', 1)->get('places')->result();
	  $place_names = array();
	  foreach ($places as $key => $place) {
	    $place_names[] = $place[]->place_name;
	  }
	  return $place_names;
	}
*/	
		
}
