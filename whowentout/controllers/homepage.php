<?php

class Homepage extends MY_Controller {

  function index() {
    $user = $this->user_model->get_user();
    $college_id = get_college_id();
    $current_time = current_time();

    $parties = $this->college_model->get_open_parties($college_id, today(TRUE));

    $data= array(
      'title'=> 'Home',
      'closing_time' => load_view('closing_time_view'),
      'doors_are_closed' => doors_are_closed(),
      'parties_dropdown' => parties_dropdown($parties),
      'has_attended_party' => $this->user_model->has_attended_party_on_date( $user->id, yesterday(TRUE) ),
    );

    if ($data['has_attended_party']) {
      $data['party'] = $this->user_model->get_attended_party( $user->id, yesterday(TRUE) );
    }

    $this->load_view('homepage_view', $data);
  }

}
