<?php

class flash
{

    static function message($message = '')
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
        }
        else {
            $message = isset($_SESSION['flash_message']) ? $message : '';
            unset($_SESSION['flash_message']);
        }
        return r::flash_message(array('message' => $message));
    }

}
