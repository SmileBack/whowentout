<?php

class ViewProfileAction extends Action
{
    function execute($user_id)
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        if ($user_id == 'me')
            $user_id = $current_user->id;
//        elseif ($user_id == $current_user->id)
//            redirect("profile/view/me");

        $user = db()->table('users')->row($user_id);

        print r::page(array(
            'content' => r::profile(array(
                'user' => $user,
                'current_user' => $current_user,
            )),
        ));
    }
}
