<?php

class ViewEntourageAction extends Action
{
    function execute()
    {
        print r::page(array(
            'content' => r::entourage(array(
                'user' => auth()->current_user(),
            )),
        ));
    }
}
