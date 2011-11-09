<?php

class FlickrPicture
{

    private $photo_data;

    function __construct($photo_data)
    {
        $this->photo_data = $photo_data;
    }

    function id()
    {
        return $this->photo_data['id'];
    }

    function url($size)
    {
        if ($size == 'thumb')
            return $this->photo_data['url_sq'];
        elseif ($size == 'large')
            return $this->photo_data['url_m'];
        else
            throw new Exception("Unsupported size $size.");
    }

}

