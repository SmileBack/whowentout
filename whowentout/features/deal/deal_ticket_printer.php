<?php

class DealTicketPrinter
{

    private $profile_picture_factory;

    function __construct(ProfilePictureFactory $profile_picture_factory)
    {
        $this->profile_picture_factory = $profile_picture_factory;
    }

    /**
     * @param $user
     * @param $event
     * @param string $orientation
     * @return WideImage_Image
     */
    function print_deal($user, $event, $orientation = 'landscape')
    {
        $venue = $event->name;
        $deal = $event->deal;
        $date = $event->date->format('M jS');
        $profile_picture = $this->profile_picture_factory->build($user);

        $profile_picture_url = $profile_picture->url('thumb');

        if ($event->deal_type == 'door')
            $redeem_message = 'SHOW AT DOOR (21+ to drink)';
        else
            $redeem_message = 'SHOW TO BARTENDER (21+ to drink)';

        $ticket = $this->print_info($user->first_name, $user->last_name, $profile_picture_url, $venue, $deal, $redeem_message, $date);

        if ($orientation == 'portrait')
            $ticket = $ticket->rotate(90);

        return $ticket;
    }


    /**
     * @param DatabaseRow $user
     * @param ProfilePicture $picture
     * @param $venue
     * @param $deal
     * @param $date
     *
     * @return WideImage_Image
     */
    private function print_info($first_name, $last_name, $profile_pic_url, $venue, $deal, $redeem_message, $date)
    {
        $ticket = $this->create_blank_ticket();

        $pic = WideImage::load($profile_pic_url);

        $this->print_picture($ticket, $pic);
        $this->print_name($ticket, $first_name, $last_name);
        $this->print_lines($ticket, explode("\n", $deal));
        $this->print_venue_and_date($ticket, $venue, $date);
        $this->print_redeem_message($ticket, $redeem_message);

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

    private function print_lines(WideImage_Image &$ticket, array $lines)
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

    private function create_blank_ticket()
    {
        return WideImage::load('./images/ticket_blank.png');
    }

    private function font_path()
    {
        return '../designs/fonts/Futura Medium.ttf';
    }

}
