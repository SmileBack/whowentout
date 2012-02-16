<?php

class ConfirmDealAction extends Action
{

    /* @var $db Database */
    private $db;

    /* @var $auth FacebookAuth */
    private $auth;

    function __construct()
    {
        $this->db = db();
        $this->auth = build('auth');
    }

    function execute()
    {
        $cell_phone_number = $_POST['user']['cell_phone_number'];

        $event = to::event($_POST['event_id']);
        $current_user = $this->auth->current_user();

        $current_user->cell_phone_number = $this->format_phone_number($cell_phone_number);
        $current_user->save();

        app()->goto_event($event);
    }

    private function format_phone_number($phone_number)
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        $num_digits = strlen($phone_number);
        if ($num_digits == 7)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $phone_number);
        elseif ($num_digits == 10)
            $phone_number = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $phone_number);

        return $phone_number;
    }

}
