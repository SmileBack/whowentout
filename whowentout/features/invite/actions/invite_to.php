<?php

class InviteToAction extends Action
{

    /**
     * @var InviteEngine
     */
    private $invite_engine;

    function __construct()
    {
        $this->auth = auth();
        $this->invite_engine = build('invite_engine');
    }

    function execute()
    {
        $event_id = $_POST['event']['id'];
        $user_id = $_POST['user']['id'];

        $event = to::event($_POST['event']['id']);
        $user = to::user($_POST['user']['id']);

        $current_user = $this->auth->current_user();

        if (!$this->invite_engine->is_invited($event, $user))
            $this->invite_engine->send_invite($event, $current_user, $user);

        $response = array('success' => true);

        $response['invite_to_form'] = r::invite_to_form(array(
            'user' => $user,
            'event' => $event,
        ))->render();

        print json_encode($response);exit;
    }


}
