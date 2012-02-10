<?php

class AdminViewDeals extends Action
{
    function execute($y = null, $m = null, $d = null)
    {
        auth()->require_admin();

        if ($y == null)
            $date = app()->clock()->today();
        else
            $date = DateTime::createFromFormat('Y/m/d', "$y/$m/$d");

        print r::admin_deals(array(
            'date' => $date,
        ));
    }
}
