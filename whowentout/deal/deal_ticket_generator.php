<?php

class DealTicketGenerator
{

    /**
     * @param DatabaseRow $user
     * @param ProfilePicture $picture
     * @param $venue
     * @param $deal
     * @param $date
     *
     * @return WideImage_Image
     */
    function generate(DatabaseRow $user, ProfilePicture $picture, $venue, $deal, $date)
    {
        $ticket = $this->blank_ticket();

        $pic = WideImage::load($picture->url('thumb'));
        $this->print_picture($ticket, $pic);

        $this->print_lines($ticket, array(
            $user->first_name . ' ' . $user->last_name,
            $venue,
            $deal,
        ));

        $this->print_date($ticket, $date);

        $this->print_show_to_bartender_message($ticket);

        return $ticket;
    }

    private function print_picture(WideImage_Image &$ticket, WideImage_Image $picture)
    {
        $picture = $picture->resize(90);
        $ticket = $ticket->merge($picture, 25, 55);
    }

    private function print_lines(WideImage_Image &$ticket, array $lines)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont('../designs/fonts/Futura Medium.ttf', 16, $ticket->allocateColor(255, 255, 255));

        $x = 130;
        $y = 55;

        foreach ($lines as $current_line) {
            $canvas->writeText($x, $y, $current_line);
            $y += 30;
        }
    }

    private function print_date(WideImage_Image &$ticket, $date)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont('../designs/fonts/Futura Medium.ttf', 10, $ticket->allocateColor(255, 255, 255));
        $canvas->writeText('right - 12', 'top + 12', $date);
    }

    private function print_show_to_bartender_message(WideImage_Image &$ticket)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont('../designs/fonts/Futura Medium.ttf', 13, $ticket->allocateColor(255, 255, 0));
        $canvas->writeText(105, 15, 'SHOW TO BARTENDER');
    }

    private function blank_ticket()
    {
        return WideImage::load('./images/ticket_blank.png');
    }

}
