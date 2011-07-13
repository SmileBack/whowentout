<?php

define('ANONYMOUS_CHECKIN_STATE', 1);
define('LOGIN_LINK_STATE', 2);

class User extends MY_Controller {
  
  function edit_save() {
    if ( ! logged_in() )
      show_404();
    
    $user = current_user();
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
      $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
      $user->save();
      set_message('Saved your info');
    }
    else {
      set_message('No changes were made.');
    }
    
    // The first time no-changes edit still counts as a save.
    if ($user->never_edited_profile()) {
      $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
      $user->save();
    }
    
    if (login_action() != NULL) {
      $action = login_action();
      if ($action['name'] == 'checkin')
        redirect('checkin');
    }
    
    redirect('dashboard');
  }
  
  function edit() {
    require_login();
    
    $this->load_view('user_edit_view', array(
      'user' => current_user(),
    ));
  }
  
  function login() {
    $user_id = post('user_id');
    if (fb()->getUser() == NULL) {
      redirect(facebook_login_url());
    }
    else {
      login();
      
      require_profile_edit();
      
      if (login_action() != NULL) {
        $action = login_action();
        if ($action['name'] == 'checkin')
          redirect('checkin');
      }
      
      redirect('dashboard');
    }
  }
  
  function fakelogin($user_id = NULL) {
    if ( ! WWO_DEBUG)
      show_404();
      
    if ($user_id != NULL) {
      fake_login($user_id);
      redirect('dashboard');
    }
    else {
      $students = current_college()->get_students();
      $this->load_view('login_view', array(
        'students' => $students,
      ));
    }
  }
  
  function logout() {
    logout();
    redirect('/');
  }
  
  function checkin() {
    $party_id = post('party_id');
    
    require_login(array(
      'name' => 'checkin',
      'party_id' => $party_id,
    ));
    
    if (login_action() != NULL) {
      $data = login_action();
      if ($data['name'] == 'checkin') {
        clear_login_action();
        $party_id = $data['party_id'];
      }
    }
    
    $user = current_user();
    $party = XParty::get($party_id);
    
    if ($party == NULL)
      show_error("Party doesn't exist.");
    
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
