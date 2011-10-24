<?php

class DashboardElement extends Element
{

    function process(&$vars)
    {
        $user = current_user();
        $college = college();

        $time = $college->get_clock()->get_time();
        $yesterday = $time->getDay(-1);
        $parties = $college->open_parties($time);

        $vars['title'] = 'Dashboard';
        $vars['user'] = $user;
        $vars['college'] = $college;
        $vars['closing_time'] = load_view('closing_time_view');
        $vars['doors_are_closed'] = ! $college->get_door()->is_open();
        $vars['parties_dropdown'] = parties_dropdown($parties);
        $vars['parties_attended'] = $user->recent_parties();
        $vars['has_attended_party'] = $user->has_attended_party_on_date($yesterday);
        $vars['top_parties'] = $college->top_parties();

        if ($vars['has_attended_party']) {
            $vars['party'] = $user->get_attended_party($yesterday);
        }
        return $vars;
    }

}
