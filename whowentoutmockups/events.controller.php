<?php

class Events extends Controller
{

    function view($role = 'user')
    {
        print r('page', array(
                          'content' => r('events_view', array(
                                                          'role' => $role,
                                                        )),
                        ));
    }

    function create()
    {
        print r('page', array(
                          'content' => r('events_create'),
                        ));
    }

    function guestlist()
    {
        print r('page', array(
                          'content' => r('events_guestlist'),
                        ));
    }

    function invite()
    {
        print r('page', array(
                          'content' => r('events_invite'),
                        ));
    }

}
