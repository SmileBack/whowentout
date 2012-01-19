<?php

class AdminViewAction extends Action
{

    function execute()
    {
        auth()->require_admin();
        print r::page(array(
                           'content' => r::admin_index(),
                      ));
    }

}
