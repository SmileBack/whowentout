<?php

class TestAction extends Action
{

    function execute()
    {
        $user = db()->table('users')->where('first_name', 'Venkat')->first();
        $event = db()->table('events')->row(11);

        $this->email_deal($user, $event);
    }


    function email_deal($user, $event)
    {
        /* @var $deal_emailer DealEmailer */
        $deal_emailer = build('deal_emailer');
        $deal_emailer->email_deal($user, $event);
    }

}
