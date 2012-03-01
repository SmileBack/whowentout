<?php

class DealEmailer
{

    /**
     * @var \DealTicketPrinter
     */
    private $deal_ticket_printer;

    /**
     * @var \Emailer
     */
    private $emailer;

    function __construct(DealTicketPrinter $deal_ticket_printer, Emailer $emailer)
    {
        $this->deal_ticket_printer = $deal_ticket_printer;
        $this->emailer = $emailer;
    }

    function email_deal($user, $event)
    {
        if (!$event->deal) // event doesn't have a deal
            return;

        $deal_file_path = $this->get_file_path($user, $event);

        $ticket = $this->deal_ticket_printer->print_deal($user, $event);
        $ticket->saveToFile($deal_file_path);

        $attachments = array($deal_file_path);
        $this->emailer->send_email($user->email, "Deal for $event->name", 'Your deal is attached.', $attachments);

        @unlink($deal_file_path);
    }

    private function get_file_path($user, $event)
    {
        return sys_get_temp_dir() . '/' . $this->get_file_name($user, $event);
    }

    private function get_file_name($user, $event)
    {
        $deal_file_name = strtolower("{$user->first_name}_{$user->last_name}_deal.jpg");
        $deal_file_name = preg_replace('/[^a-z_.]/', '', $deal_file_name);
        return $deal_file_name;
    }

}
