<?php

define('ANONYMOUS_CHECKIN_STATE', 1);
define('LOGIN_LINK_STATE', 2);

class User extends MY_Controller {
  
  function edit_save() {
    if ( ! logged_in() )
      show_404();
    
    $user = current_user();
    
    if (post('op') == 'Upload') {
      $user->upload_pic();
      redirect('user/edit');
    }
    elseif (post('op') == 'Use Facebook Pic') {
      $user->use_facebook_pic();
      set_message('Now using your profile pic from Facebook.');
      redirect('user/edit');
    }
    elseif (post('op') == 'Save') {
      if (post('width') && post('height')) {
        $user->crop_pic( post('x'), post('y'), post('width'), post('height') );
        $user->hometown = post('hometown');
        $user->grad_year = post('grad_year');
      }
      
      if ($user->changed()) {
        $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
        $user->save();
        set_message('Saved your info');
      }
      
      // The first time no-changes edit still counts as a save.
      if ($user->never_edited_profile()) {
        $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
        $user->save();
      }
      
      if ( login_action() != NULL && $user->can_use_website() ) {
        $action = login_action();
        if ($action['name'] == 'checkin')
          redirect('checkin');
      }

      redirect('dashboard');
    }
    
    show_404();
  }
  
  function edit() {
    require_login();
    
    $this->load_view('user_edit_view', array(
      'user' => current_user(),
      'is_missing_info' => current_user()->is_missing_info(),
      'missing_info' => current_user()->get_missing_info(),
    ));
  }
  
  function login() {
    $user_id = post('user_id');
    if (fb()->getUser() == NULL) {
      redirect(facebook_login_url());
    }
    else {
      login();
      
      enforce_restrictions();
      
      if (login_action() != NULL) {
        $action = login_action();
        if ($action['name'] == 'checkin')
          redirect('checkin');
      }
      
      redirect('dashboard');
    }
  }
  
  function fakelogin($user_id = NULL) {
    if (ENVIRONMENT == 'production')
      show_404();
    
    if ($user_id != NULL) {
      fake_login($user_id);
      redirect('dashboard');
    }
    else {
      $students = college()->get_students();
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
    $user = current_user();
    
    require_login(array(
      'name' => 'checkin', //action name
      'party_id' => $party_id,
    ));
    
    if (login_action_exists()) {
      $action = login_action();
      if ($action['name'] == 'checkin') {
        clear_login_action();
        $party_id = $action['party_id'];
      }
    }
    
    $party = party($party_id);
    
    if ($party == NULL)
      show_error("Party with id = $party_id doesn't exist.");
    
    $party_date = new DateTime($party->date, college()->timezone);
    
    if ( $user->can_checkin($party) ) {
      $user->checkin($party);
      redirect("party/$party->id");
    }
    elseif ( $user->reason() == REASON_ALREADY_ATTENDED_PARTY) {
      $other_party = $user->get_attended_party($party_date);

      if ($other_party->id == $party->id)
        set_message("You have already checked into {$party->place->name}.");
      else
        set_message("You have already checked into {$other_party->place->name}, so you can't checkin to {$party->place->name}.");
        
      redirect("party/$other_party->id");
    }
    else {
      set_message(get_reason_message($user->reason()));
      redirect('/');
    }
    
  }
  
  function smile() {
    $user = current_user();
    
    $party_id = post('party_id');
    $receiver_id = post('receiver_id');
    
    $receiver = user($receiver_id);
    
    if ( ! $user->can_smile_at($receiver->id, $party_id) ) {
      show_error("Smile denied.");
    }
    
    $user->smile_at($receiver_id, $party_id);
    set_message("Smiled at $receiver->full_name");
    
    redirect("party/$party_id");
  }
  
  function mutual_friends($target_id) {
    $user = current_user();
    $target = user($target_id);
    
    if (!$user || !$target) {
      print "Invalid request.";exit;
    }
    
    $mutual_friends = $user->mutual_friends($target);
    
    $this->load->view('mutual_friends_view', array(
      'mutual_friends' => $mutual_friends, 
    ));
  }
  
}
