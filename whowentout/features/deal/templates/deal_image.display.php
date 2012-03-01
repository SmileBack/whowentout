<?php

class Deal_Image_Display extends Display
{

    protected $defaults = array(
        'orientation' => 'landscape',
    );

    function process()
    {
        $this->ticket_url = $this->get_deal_url($this->user, $this->event, $this->orientation);
    }

    private function get_deal_url($user, $event, $orientation = 'landscape')
    {
        /* @var $deal_tickets DealTicketUrlRepository */
        $deal_tickets = build('deal_ticket_url_repository');
        return $deal_tickets->url($user, $event, $orientation);
    }

}