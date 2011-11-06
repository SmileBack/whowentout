<?php

class FlickrPicture
{

    private $photo_data;

    /**
     * @var phpFlickr
     */
    private $f;

    function __construct($photo_data, phpFlickr $f)
    {
        $this->photo_data = $photo_data;
        $this->f = $f;
    }

    function id()
    {
        return $this->photo_data['id'];
    }

    function url($size)
    {
        if ($size == 'thumb')
            return $this->f->buildPhotoURL($this->photo_data, 'square');
        elseif ($size == 'large')
            return $this->f->buildPhotoURL($this->photo_data, 'medium');
        else
            throw new Exception("Unsupported size $size.");
    }

}

