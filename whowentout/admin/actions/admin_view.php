<?php

class AdminViewAction extends Action
{

    function execute()
    {
        auth()->require_admin();
        print r::admin_index();
    }

}
