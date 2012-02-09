<?php

class AdminDestroyUserAction extends Action
{

    function execute($user_id)
    {
        auth()->require_admin();

        $user = db()->table('users')->row($user_id);

        $is_current_user = ($user == auth()->current_user());

        $full_name = $user->first_name . ' ' . $user->last_name;

        db()->table('entourage_requests')->where('sender_id', $user_id)->destroy();
        db()->table('entourage_requests')->where('receiver_id', $user_id)->destroy();

        db()->table('entourage')->where('user_id', $user_id)->destroy();
        db()->table('entourage')->where('friend_id', $user_id)->destroy();

        db()->table('user_networks')->where('user_id', $user_id)->destroy();

        db()->table('user_friends')->where('user_id', $user_id)->destroy();
        db()->table('user_friends')->where('friend_id', $user_id)->destroy();

        db()->table('profile_pictures')->where('user_id', $user_id)->destroy();

        db()->table('invites')->where('sender_id', $user_id)->destroy();
        db()->table('invites')->where('receiver_id', $user_id)->destroy();

        db()->table('checkins')->where('user_id', $user_id)->destroy();

        // remove events ref
        db()->execute('UPDATE events SET user_id = NULL WHERE user_id = :id', array('id' => $user_id));

        db()->table('users')->where('id', $user_id)->destroy();

        flash::message("Destroyed $full_name.");

        if ($is_current_user) {
            auth()->logout();
        }

        redirect('/');
    }

}