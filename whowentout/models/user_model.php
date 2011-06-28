<?php

class User_model extends CI_Model {
		
	function get_user() {
		return $this->db
			->select('users.id AS id, first_name, last_name, college_name, grad_year, profile_pic,
				email, gender, date_of_birth')
			->from('users')
			->where('users.id', get_user_id())
			->join('colleges', 'users.college_id = colleges.id')
			->get()->row();
	}
  
}		







		
		//return $this->db->where('user_id', 1)->get('users')->row();
		
		//$users = $this->db->get_where('users', array('user_id' => 1))->result();
		//return $users[0];
		
		// the function row() gets the row specified, while the function result() gets an array of rows;
		// return $this->db->get_where('users', array('user_id' => 1))->row();