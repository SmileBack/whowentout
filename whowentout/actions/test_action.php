<?php

class TestAction extends Action
{
    function execute()
    {
        $user = auth()->current_user();
        $result = $user->checkins->order_by('event.date');

        foreach ($result as $row) {
            krumo::dump($row->event->name);
            krumo::dump($row->event->date);
        }
    }
}
