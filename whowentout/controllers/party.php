<?php

class Party extends MY_Controller {
	
	function page($id) {
		$this->load->model('party_model');
		$this->load->model('user_model');
		$this->load->config('wwo');
		
		$user= $this->user_model->get_user();
		
		try {
			$data= array(
				'title'=> 'Party',
				'party_attendees'=> $this->party_model->get_party_attendees($id),
				'profile_pic_size'=> $this->config->item('profile_pic_size'),
				'party'=> $this->party_model->get_party($id, $user->id),
			);
			
			$this->load_view('party_view', $data);
		} catch (Exception $e) {
			show_404();
		}
	}
}


















/* 'user'=>
		array(
			'first_name'=> 'Dan', 
			'last_initial'=> 'B', 
			'age'=> 23, 
			'college'=> 'Stanford', 
			'grad_year'=> "'12",
			'parties_attended' => get_parties_attended(),
		);

			'parties_attended'=> array(
		  		array('place'=> 'McFaddens', 'place_admin'=> 'Alex Webb', 'date'=> 'Saturday, September 17th', 
					'smiles_received'=> '9 girls', 'smiles_remaining'=> '3 smiles', 'matches'=> 'Jennifer L.'),
		 		array('place'=> 'Sigma Chi', 'place_admin'=> 'Alex Webb', 'date'=> 'Saturday, September 16th', 
					'smiles_received'=> '7 girl', 'smiles_remaining'=> '0 smiles', 'matches'=> 'Clara S.'),
				array('place'=> 'Sky Bar', 'place_admin'=> 'Alex Webb', 'date'=> 'Saturday, September 15th', 
					'smiles_received'=> '0 girls', 'smiles_remaining'=> '0 smiles', 'matches'=> 'Marissa O.'),
			);
-----------------
			
	'party_attendees'=> array(
		array(
			'name'=> 'Clara S.',
			'age'=> 20,
			'school'=> 'GWU',
			'gradyear'=> "'13",
			'mutual_friends'=> 8,
			'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Clara\'s picture', 'class'=> 'ClaraPic'),
			),
		array(
			'name'=> 'Natalie E.',
			'age'=> 21,
			'school'=> 'GWU',
			'gradyear'=> "'12",
			'mutual_friends'=> 16,
			'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Natalie\'s picture', 'class'=> 'NataliePic'),
			),
		array(
			'name'=> 'Marissa O.',
			'age'=> 20,
			'school'=> 'GWU',
			'gradyear'=> "'13",
			'mutual_friends'=> 12,
			'image'=> array('src'=> 'epcotmexicopicture.jpg', 'alt'=> 'Marissa\'s picture', 'class'=> 'MarissaPic'),
			),
		);
*/
