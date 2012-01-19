<?php

class AdminViewPlacesAction extends Action
{

    function execute()
    {
        auth()->require_admin();

        print r::page(array(
                           'content' => r::admin_places(array(
                                                       'places' => app()->database()->table('places'),
                                                  )),
                      ));
    }

}