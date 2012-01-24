<?php

class flash
{

    static function message($message = '', $type = 'notice')
    {
        if ($message) {
            $_SESSION['flash_message'] = array(
                'message' => $message,
                'type' => $type,
            );
        }
        else {
            $message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : '';
            unset($_SESSION['flash_message']);
        }
        return r::flash_message(array('message' => $message['message'], 'type' => $message['type']));
    }

    static function error($message = '')
    {
        return static::message($message, 'error');
    }

}
