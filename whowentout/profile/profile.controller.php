<?php

class Profile_Controller extends Controller
{

    function edit()
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        print r::page(array(
                    'content' => r::profile(array(
                                                'user' => $current_user,
                                            )),
                ));
    }

}
