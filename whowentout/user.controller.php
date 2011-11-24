<?php

class User_Controller extends Controller
{
    
    function create()
    {
        $attributes = app()->input->post('user');
        if ( app()->can('create_user', $attributes)) {
            app()->users()->create($attributes);
        }
    }

    function update($user_id)
    {
        $attributes = app()->input()->post('user');
        if ( app()->can('update_user', $user_id, $attributes) ) {
            $user = app()->users()->fetch($user_id);
            $user->set($attributes);
            $user->save();
        }
    }

    function destroy($user_id)
    {
        if ( app()->can('destroy_user', $user_id) ) {
            app()->users()->destroy($user_id);
        }
    }

    function login()
    {
        if ( app()->current_user()->can('login') ) {
            app()->auth()->login();
        }
    }

    function logout()
    {
        if ( app()->current_user()->can('logout') ) {
            app()->auth()->logout();
        }
    }
    
}
