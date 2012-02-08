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
    function generate(DatabaseRow $user, ProfilePicture $picture, $venue, $deal, $deal_type, $date)
    {
        $ticket = $this->blank_ticket();

        $pic = WideImage::load($picture->url('thumb'));
        $this->print_picture($ticket, $pic);

        $this->print_name($ticket, $user->first_name, $user->last_name);

        $this->print_deal($ticket, explode("\n", $deal));

        $this->print_venue_and_date($ticket, $venue, $date);

        if ($deal_type == 'door')
            $this->print_redeem_message($ticket, 'SHOW AT DOOR (21+ to drink)');
        elseif ($deal_type == 'bar' || true)
            $this->print_redeem_message($ticket, 'SHOW TO BARTENDER (21+ to drink)');

        return $ticket;
    }

    private function print_picture(WideImage_Image &$ticket, WideImage_Image $picture)
    {
        $picture = $picture->resize(90);
        $ticket = $ticket->merge($picture, 25, 55);
    }

    private function print_venue_and_date(WideImage_Image &$ticket, $venue, $date)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont($this->font_path(), 14, $ticket->allocateColor(255, 204, 51));

        $canvas->writeText(130, 55, $venue . ', ' . $date);
    }

    private function print_deal(WideImage_Image &$ticket, array $lines)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont($this->font_path(), 14, $ticket->allocateColor(255, 255, 255));

        $x = 130;
        $y = 85;

        foreach ($lines as $current_line) {
            $canvas->writeText($x, $y, $current_line);
            $y += 25;
        }
    }

    private function print_name(WideImage_Image &$ticket, $first_name, $last_name)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont($this->font_path(), 14, $ticket->allocateColor(255, 255, 255));

        $canvas->writeText(25, 180, $first_name);
        $canvas->writeText(25, 200, $last_name);
    }

    private function print_redeem_message(WideImage_Image &$ticket, $message)
    {
        $canvas = $ticket->getCanvas();
        $canvas->useFont($this->font_path(), 13, $ticket->allocateColor(255, 255, 0));
        $canvas->writeText(25, 15, $message);
    }

    private function get_text_width($font_size, $text)
    {
        $box = imagettfbbox($font_size, 0, $this->font_path(), $text);
        return $box[2] - $box[0];
    }

    private function blank_ticket()
    {
        return WideImage::load('./images/ticket_blank.png');
    }

    private function font_path()
    {
        return '../designs/fonts/Futura Medium.ttf';
    }

}
