<?php

class User extends MY_Controller {
  
  function login() {
    $user_id = post('user_id');
    
    if ($user_id != NULL) {
      fake_login($user_id);
      redirect(login_destination());
    }
    elseif (fb()->getUser() != NULL) {
      login();
      redirect(login_destination());
    }
    else {
      $this->load_view('login_view');
    }
    
  }
  
  function logout() {
    logout();
    redirect('/');
  }
  
  function checkin() {
    $user = current_user();
    
    //user just logged in
    if (login_action()) {
      $party_id = login_post('party_id');
    }
    else {
      $party_id = post('party_id');
    }
    
    if ( ! logged_in()) {
      require_login(array(
        'message' => 'Login in so that you can checkin to ' . XParty::get($party_id)->place->name . '.',
      ));
    }
    
    if ( ! $user->can_checkin($party_id) ) {
      show_error("You can't checkin to more than one party for a given day.");
    }
    
    $user->checkin($party_id);
    clear_login_action();
    
    redirect("party/$party_id");
  }
  
  function smile() {
    $user = current_user();
    
    $party_id = post('party_id');
    $receiver_id = post('receiver_id');
    
    if ( ! $user->can_smile_at($receiver_id, $party_id) ) {
      show_error("Smile denied.");
    }
    
    $user->smile_at($receiver_id, $party_id);
    
    redirect("party/$party_id");
  }
	
}
