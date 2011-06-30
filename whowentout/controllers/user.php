<?php

class User extends MY_Controller {
	
  function checkin() {
    $party_id = $this->input->post('party_id');
    $user_id = get_user_id();
    
    if (!$this->user_model->can_checkin($user_id, $party_id)) {
      show_error("You can't checkin to more than one party for a given day.");
    }
    
    $this->user_model->checkin($user_id, $party_id);
    redirect("party/$party_id");
  }
	
}
