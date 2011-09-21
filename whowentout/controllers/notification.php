<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller
{

    function unread()
    {
        $this->check_access();
        
        $this->json(array(
                         'success' => TRUE,
                         'notifications' => $this->notification->unread_notifications(current_user()),
                    ));
    }

    function mark_as_read($id)
    {
        $this->check_access();

        $notification = $this->notification->get($id);

        if ($notification->user_id != current_user()->id) {
            $this->json_failure("You don't own this notification.");
        }

        $this->notification->mark_as_read($notification->id);

        $this->json_success();
    }

    function check_access()
    {
        if (!logged_in()) {
            $this->json(array(
                             'success' => FALSE,
                             'error' => 'Not logged in.',
                        ));
        }
    }

}