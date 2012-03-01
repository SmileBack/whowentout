<?php

class DealTicketUrlRepository
{

    /* @var $deal_ticket_printer DealTicketPrinter */
    private $deal_ticket_printer;
    private $deal_ticket_storage;

    function __construct(DealTicketPrinter $deal_ticket_printer, ImageRepository $deal_ticket_images)
    {
        $this->deal_ticket_printer = $deal_ticket_printer;
        $this->deal_ticket_storage = $deal_ticket_images;
    }

    function url($user, $event, $orientation)
    {
        $ticket = $this->deal_ticket_printer->print_deal($user, $event, $orientation);

        $ticket_id = $this->get_ticket_id($user, $event, $orientation);
        $this->deal_ticket_storage->create_from_image($ticket_id, $ticket);

        return $this->deal_ticket_storage->url($ticket_id);
    }

    private function get_ticket_id($user, $event, $orientation)
    {
        return $event->id . '_' . $user->id . '_' . $orientation;
    }

}
