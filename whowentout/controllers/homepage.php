<?php

class Homepage extends MY_Controller {

  function index() {
    $user = XUser::current();
    $college = XCollege::current();
    $current_time = current_time();
    
    $parties = $college->open_parties(current_time());
    
    $data = array(
      'title'=> 'Home',
      'closing_time' => load_view('closing_time_view'),
      'doors_are_closed' => doors_are_closed(),
      'parties_dropdown' => parties_dropdown($parties),
      'has_attended_party' => FALSE,
    );

    $this->load_view('homepage_view', $data);
  }

}
