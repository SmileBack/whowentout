<?php

class Dashboard extends MY_Controller
{
  
  function index() {
    if ( ! logged_in() ) {
      redirect('login');
    }
    
    enforce_restrictions();
    raise_event('page_load', uri_string());
    
    $user = current_user();
    $college = college();
    $time = current_time();
    $yesterday = $college->yesterday(TRUE);
    $parties = $college->open_parties($time);
    
    $data = array(
      'title'=> 'Dashboard',
      'user'=> $user,
      'college' => $college,
      'closing_time' => load_view('closing_time_view'),
      'doors_are_closed' => $college->doors_are_closed(),
      'parties_dropdown' => parties_dropdown($parties),
      'parties_attended' => $user->recent_parties(),
      'has_attended_party' => $user->has_attended_party_on_date( $yesterday ),
      'top_parties' => $college->top_parties(),
    );
    
    if ($data['has_attended_party']) {
      $data['party'] = $user->get_attended_party( $yesterday );
    }
    
    $this->load_view('dashboard_view', $data);
  }
  
  function top_parties() {
    print load_view('sections/top_parties_view', array(
      'college' => college(), 
    ));
  }
  
  function past_top_parties() {
    $this->load_view('past_top_parties_view', array(
      'html' => get_option('past_top_parties_html', ''),
    ));
  }
  
  function where_friends_went() {
    $this->load_view('where_friends_went_past_view');
  }
  
  function where_friends_went_data() {
    $date = new DateTime(post('date'), college()->timezone);
    $response = where_friends_went_pie_chart_data($date);
    print json_encode($response);
  }
  
}
