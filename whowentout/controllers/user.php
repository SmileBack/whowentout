<?php

class User extends MY_Controller {
  
  function edit() {
    if ( ! logged_in())
      show_404();
    
    $user = current_user();
    
    $data = array(
     'user' => current_user(),  
    );
    
    if (post('width') && post('height')) {
      $user->pic_x = post('x');
      $user->pic_y = post('y');
      $user->pic_width = post('width');
      $user->pic_height = post('height');
      $user->refresh_image('normal');
      $user->refresh_image('thumb');
    }
    
    if (post('hometown')) {
      $user->hometown = post('hometown');
    }
    
    if (post('grad_year')) {
      $user->grad_year = post('grad_year');
    }
    
    if ($user->changed()) {
      $user->save();
      set_message("Saved your info");
      redirect('dashboard');
    }
    
    $this->load_view('user_edit_view', $data);
  }
  
  function login() {
    ci()->session->keep_flashdata('login_action');
    
    $user_id = post('user_id');
    if (fb()->getUser() == NULL) {
      redirect(facebook_login_url());
    }
    else {
      login();
      redirect(login_destination());
    }
    
  }
  
  function fakelogin() {
    if ( ! WWO_DEBUG)
      show_404();
      
    $user_id = post('user_id');
    
    if ($user_id != NULL) {
      fake_login($user_id);
      redirect(login_destination());
    }
    else {
      $this->load_view('login_view', array(
        'students_dropdown' => $this->_students_dropdown(),
      ));
    }
  }
  
  private function _students_dropdown() {
    $students = current_college()->students;
    $options = array();
    foreach ($students as $student) {
      $options[$student->id] = $student->full_name;
    }
    return form_dropdown('user_id', $options);
  }
  
  function logout() {
    logout();
    redirect('/');
  }
  
  function checkin() {
    require_login();
    
    $party_id = post('party_id');
    $user = current_user();
    $party = XParty::get($party_id);
    $party_date = new DateTime($party->date, get_college_timezone());
    
    // User has already attended a party on the date
    if ( $user->has_attended_party_on_date($party_date) ) {
      $other_party = $user->get_attended_party($party_date);
      
      if ($other_party->id == $party->id)
        set_message("You have already checked into {$party->place->name}.");
      else
        set_message("You have already checked into {$other_party->place->name}, so you can't checkin to {$party->place->name}.");
        
      redirect("party/$other_party->id");
    }
    
    if ( ! $user->can_checkin($party->id) ) {
      show_error("You can't checkin.");
    }
    else {
      $user->checkin($party->id);
    }
    
    redirect("party/$party->id");
  }
  
  function smile() {
    $user = current_user();
    
    $party_id = post('party_id');
    $receiver_id = post('receiver_id');
    
    $receiver = XUser::get($receiver_id);
    
    if ( ! $user->can_smile_at($receiver->id, $party_id) ) {
      show_error("Smile denied.");
    }
    
    $user->smile_at($receiver_id, $party_id);
    set_message("Smiled at $receiver->full_name");
    
    redirect("party/$party_id");
  }
	
}
