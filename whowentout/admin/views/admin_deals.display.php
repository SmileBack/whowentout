<?php

class Admin_Deals_Display extends Display
{
    function process()
    {
        $this->checkins = db()->table('checkins')->where('event.date', $this->date);
    }
}
