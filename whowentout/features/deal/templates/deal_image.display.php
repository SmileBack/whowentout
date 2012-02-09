<?php

class Deal_Image_Display extends Display
{
    function process()
    {
        /* @var $repo ImageRepository */
        $repo = build('ticket_repository');

        $event = $this->event;
        $user = $this->user;

        $venue = $event->name;
        $deal = $event->deal;
        $deal_type = $event->deal_type;
        $date = $event->date->format('M jS');
        $profile_picture = build('profile_picture', $user);

        $gen = new DealTicketGenerator();

        $ticket = $gen->generate($user, $profile_picture, $venue, $deal, $deal_type, $date);

        if ($this->orientation == 'portrait')
            $ticket = $ticket->rotate(90);

        $ticket_id = $event->id . '_' . $user->id . '_' . $this->orientation;
        $repo->create_from_image($ticket_id, $ticket);

        $this->ticket_url = $repo->url($ticket_id);
    }
}