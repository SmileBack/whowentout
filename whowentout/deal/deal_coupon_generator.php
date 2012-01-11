<?php

class DealCouponGenerator
{

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

        $ticket->saveToFile('./images/woo.png');
    }

    private function print_picture(WideImage_Image &$ticket, $picture)
    {
        $ticket = $ticket->merge($picture, 25, 40);
    }

    private function print_lines(WideImage_Image $image, array $lines)
    {
        $canvas = $image->getCanvas();
        $canvas->useFont('../designs/fonts/Futura Medium.ttf', 16, $image->allocateColor(255, 255, 255));

        $x = 145;
        $y = 40;

        foreach ($lines as $current_line) {
            $canvas->writeText($x, $y, $current_line);
            $y += 30;
        }
    }

    private function print_date(WideImage_Image $image, $date)
    {
        $canvas = $image->getCanvas();
        $canvas->useFont('../designs/fonts/Futura Medium.ttf', 12, $image->allocateColor(255, 255, 255));
        $canvas->writeText('right - 12', 'top + 12', $date);
    }

    private function blank_ticket()
    {
        return WideImage::load('./images/ticket_blank.png');
    }

}
