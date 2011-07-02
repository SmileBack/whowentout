<?php

class User extends MY_Controller {
  
  function login() {
    $user_id = $this->input->post('user_id');
    if ($user_id != NULL) {
      XUser::login($user_id);
      redirect('dashboard');
    }
    else {
      $this->load_view('login_view');
    }
  }
  
  function logout() {
    XUser::logout();
    redirect('/');
  }
  
  function checkin() {
    $party_id = $this->input->post('party_id');
    $user = XUser::current();
    
    if ( ! $user->can_checkin($party_id) ) {
      show_error("You can't checkin to more than one party for a given day.");
    }
    
    $user->checkin($party_id);
    
    redirect("party/$party_id");
  }
  
  function smile() {
    $user = XUser::current();
    
    $party_id = $this->input->post('party_id');
    $receiver_id = $this->input->post('receiver_id');
    
    if ( ! $user->can_smile_at($receiver_id, $party_id) ) {
      show_error("Smile denied.");
    }
    
    $user->smile_at($receiver_id, $party_id);
    
    redirect("party/$party_id");
  }
	
}
