<?php

class EmailDealJob extends Job
{

    function run()
    {
        /* @var $deal_emailer DealEmailer */
        $deal_emailer = build('deal_emailer');

        $checkin_id = $this->options['checkin_id'];
        $checkin = db()->table('checkins')->row($checkin_id);

        $user = $checkin->user;
        $event = $checkin->event;

        if ($event->deal) {
            $deal_emailer->email_deal($user, $event);
        }
    }

}
