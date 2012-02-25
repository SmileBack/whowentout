<?php

class AdminUpdateEmailAction extends Action
{

    /* @var $linker FacebookEmailLinker */
    private $linker;

    function __construct()
    {
        /* @var $linker FacebookEmailLinker */
        $this->linker = build('facebook_email_linker');
    }

    function execute()
    {
        auth()->require_admin();

        $data = $_POST['user'];
        $op = $_POST['op'];
        $email = null;

        $user = db()->table('users')->row($data['id']);
        $network = $this->get_network($user);

        if ($op == 'update') {
            $email = $data['email'];
        }
        elseif ($op == 'lookup') {
            $match = $this->linker->get_match($network, $user->first_name . ' ' . $user->last_name, $user->facebook_id);
            $email = $match->email;
        }

        if ($email) {
            $user->email = $email;
            $user->save();
            $message = "Changed email of $user->first_name $user->last_name to $user->email.";

            if (isset($match))
                $message .= "(Name = $match->name)";

            flash::message($message);
        }
        else {
            flash::message("No e-mail for $user->first_name $user->last_name.");
        }

        redirect('admin/emails');
    }

    function get_network($user)
    {
        $valid_networks = array('GWU', 'Georgetown', 'Stanford', 'Maryland');
        $networks = $user->networks->where('name', $valid_networks)->collect('name');
        return array_pop($networks);
    }

}
