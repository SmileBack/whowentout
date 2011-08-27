<?php

class Homepage extends MY_Controller {

  function index() {
    if (logged_in())
      redirect('dashboard');
    
    $user = current_user();
    $college = college();
    $current_time = current_time();
    
    $parties = $college->open_parties(current_time());
    
    $data = array(
      'title' => 'WhoWentOut',
      'closing_time' => load_view('closing_time_view'),
      'doors_are_closed' => $college->doors_are_closed(),
      'parties_dropdown' => parties_dropdown($parties),
      'has_attended_party' => FALSE,
    );

    $this->load_view('homepage_view', $data);
  }

}
