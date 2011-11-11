<?php

abstract class ImageTransformation
{

    protected $options = array();

    function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * @abstract
     * @param WideImage_Image $img
     * @return WideImage_Image
     */
    abstract function transform(WideImage_Image $img);
}
