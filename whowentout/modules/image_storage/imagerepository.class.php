<?php

require_once APPPATH . 'third_party/wideimage/wideimage.php';

class ImageRepository
{

    /* @var $storage FileRepository */
    private $storage;

    function __construct(FileRepository $storage)
    {
        $this->storage = $storage;
    }
    
    /**
     *
     * @param WideImage $img
     * @param int $id
     * @param string $preset
     */
    function create_from_image($key, WideImage_Image $img)
    {
        $temp_image_filepath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_filepath);
        $this->storage->create("$key.source.jpg", $temp_image_filepath);
    }

}
