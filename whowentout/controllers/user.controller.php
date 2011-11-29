<?php

class User_Controller extends Controller
{

    function login()
    {
        app()->auth()->login();

        app()->notify_user("Logged in as " . app()->auth()->current_user()->full_name . ".");

        app()->show_page('event_calendar', array(
                                                'date' => app()->today(),
                                           ));
    }

    function logout()
    {
        $current_user = app()->auth()->current_user();

        app()->auth()->logout();

        app()->notify_user("Logged out of $current_user->full_name.");

        app()->show_page('event_calendar', array(
                                                'date' => app()->today(),
                                           ));
    }

    function create()
    {
        $attributes = app()->input->post('user');
        $user = app()->users()->create($attributes);

        app()->show_page('event_calendar', array(
                                                'date' => app()->today(),
                                           ));
    }

    function profile($user_id)
    {
        $user = app()->users()->find($user_id);

        app()->show_page('user_profile', array(
                                              'user' => $user,
                                         ));
    }

    function update($user_id)
    {
        $attributes = app()->input()->post('user');
        $user = app()->users()->find($user_id);
        $user->set($attributes);
        $user->save();

        app()->show_page('user_profile', array(
                                              'user' => $user,
                                         ));
    }

    function destroy($user_id)
    {
        app()->users()->destroy($user_id);
    }

}
