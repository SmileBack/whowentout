<?php

class User extends MY_Controller {
  
  function login() {
    $user_id = $this->input->post('user_id');
    if ($user_id != NULL) {
      set_user_id($user_id);
      redirect('dashboard');
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
    $user_id = get_user_id();
    $party_id = $this->input->post('party_id');
    
    if (!$this->user_model->can_checkin($user_id, $party_id)) {
      show_error("You can't checkin to more than one party for a given day.");
    }
    
    $this->user_model->checkin($user_id, $party_id);
    redirect("party/$party_id");
  }
  
  function smile() {
    $user_id = get_user_id();
    $party_id = $this->input->post('party_id');
    $receiver_id = $this->input->post('receiver_id');
    
    if (!$this->user_model->can_smile_at($user_id, $receiver_id, $party_id)) {
      show_error("Smile denied.");
    }
    
    $this->user_model->smile_at($user_id, $receiver_id, $party_id);
    
    redirect("party/$party_id");
  }
	
}
