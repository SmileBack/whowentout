<?php

class ViewEntourageAction extends Action
{
    function execute()
    {
        print r::entourage(array(
            'user' => auth()->current_user(),
        ));
    }
}
