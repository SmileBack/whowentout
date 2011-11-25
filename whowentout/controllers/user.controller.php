<?php

class User_Controller extends Controller
{

    function login()
    {
        app()->auth()->login();
    }

    function logout()
    {
        app()->auth()->logout();
    }

    function create()
    {
        $attributes = app()->input->post('user');
        app()->users()->create($attributes);
    }

    function update($user_id)
    {
        $attributes = app()->input()->post('user');
        $user = app()->users()->find($user_id);
        $user->set($attributes);
        $user->save();
    }

    function destroy($user_id)
    {
        app()->users()->destroy($user_id);
    }
    
}
