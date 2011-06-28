<?php

class Dashboard extends MY_Controller {
		
	function index() {
    $date = strtotime('2011-05-24');
		$user = $this->user_model->get_user();
		
    $parties = model('college_model')->get_parties(get_college_id(), $date);
    
    
    $party_date = strtotime( array_first($parties)->party_date );
    
		$data = array(
      'title'=> 'Dashboard',
      'closing_time' => load_view('closing_time_view'),
      'parties_dropdown' => parties_dropdown(get_college_id(), $date),
      'user'=> $user,
      'parties_attended'=> $this->party_model->get_recent_parties_attended($user->id),
      'has_attended_party' => $this->user_model->has_attended_party($user->id, $party_date),
		);
    
    if ($data['has_attended_party']) {
      $data['party'] = $this->user_model->get_attended_party($user->id, $party_date);
    }
		
		$this->load_view('dashboard_view', $data);
	}
}
