<?php

/**
 * @property $event DatabaseRow
 * @property $user DatabaseRow
 */
class Deal_Preview_Display extends Display
{

    function process()
    {
        /* @var $repo ImageRepository */
        $repo = build('ticket_repository');

        $event = $this->event;
        $user = $this->user;

        $venue = $event->name;
        $deal = $event->deal;
        $date = $event->date->format('m.d.Y');
        $profile_picture = build('profile_picture', $user);

        $gen = new DealTicketGenerator();
        $ticket = $gen->generate($user, $profile_picture, $venue, $deal, $date);

        $repo->create_from_image($event->id . '_' . $user->id, $ticket);
        $this->ticket_url = $repo->url($event->id . '_' . $user->id);
    }

}
