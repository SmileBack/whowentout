<?php

/**
 * @property $event DatabaseRow
 * @property $user DatabaseRow
 */
class Deal_Preview_Display extends Display
{

    function process()
    {
        $event = $this->event;
        $user = $this->user;

        $venue = $event->name;
        $deal = $event->deal;
        $date = $event->date->format('m.d.Y');
        $profile_picture = factory()->build('profile_picture', $user);

        $gen = new DealTicketGenerator();
        $ticket = $gen->generate($user, $profile_picture, $venue, $deal, $date);

        $this->ticket_url = "/tickets/ticket_{$event->id}_{$user->id}.png";

        $ticket->saveToFile('.' . $this->ticket_url);
    }

}
