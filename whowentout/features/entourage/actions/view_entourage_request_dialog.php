<?php

class ViewEntourageRequestDialog extends Action
{

    function execute()
    {
        if ($this->is_ajax()) {
            $this->execute_ajax();
        }
        else {
            $this->execute_page();
        }
    }

    function execute_ajax()
    {
        print r::entourage_invite();
    }

    function execute_page()
    {
        $current_user = auth()->current_user();

        print r::page(array(
            'content' => r::profile(array(
                'user' => $current_user,
                'current_user' => $current_user,
            )),
        ));
    }

}
