<?php

class Ajax extends MY_Controller {

	/* 
	* post-conditions: 	return updated smiles_remaining
	* 					"smile at her" button will become disabled  
	*/	
	/*
	* Pre-condition: user presses submit, and we receive the place and the date (the date from the night before)
	* Post-condition: This function will add a row to the party_attendees table
	*/
	
	function party_attended() {
		$this->load->library('input');
		$this->load->helper('date');
		$place_id= $this->input->post('place_id');
		$party_date= gmt_to_local(now(), 'UM5', TRUE);
		exit($party_date);
	}
	
	function smile() {
		
			
	}
	
}