<?php

class AdminViewDeals extends Action
{
    function execute()
    {
        auth()->require_admin();

        print r::admin_deals(array(
            'date' => app()->clock()->today(),
        ));
    }
}
