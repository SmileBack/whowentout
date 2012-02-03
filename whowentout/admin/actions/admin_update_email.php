<?php

class AdminUpdateEmailAction extends Action
{
    function execute()
    {
        auth()->require_admin();

        $data = $_POST['user'];

        $user = db()->table('users')->row($data['id']);
        $user->email = $data['email'];
        $user->save();

        flash::message("Changed email of $user->first_name $user->last_name to $user->email.");

        redirect('admin/emails');
    }
}
