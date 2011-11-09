<?php

class FlickrGallery
{

    private $api_key = '7a8c13b831347f7b325013797fa85ca7';
    private $secret_key = 'b2b59ba403b9e34b';
    private $token = '72157627962087007-341309d00bf115ab-69464164';

    /**
     * @var phpFlickr
     */
    private $f;
    
    private $id;

    private $gallery_photo_data = array();
    private $pictures = array();

    function __construct($id)
    {
        $this->id = $id;
        $this->f = new phpFlickr($this->api_key, $this->secret_key);
        $this->load_pictures();
    }
    
    function id()
    {
        return $this->id;
    }

    function pictures()
    {
        return $this->pictures;
    }

    private function load_pictures()
    {
        $this->gallery_photo_data = $this->f->photosets_getPhotos($this->id, 'url_sq,url_t,url_s,url_m,url_o');
        krumo::dump($this->gallery_photo_data);
        foreach ($this->gallery_photo_data['photoset']['photo'] as $k => $photo_data) {
            $this->pictures[] = new FlickrPicture($photo_data);
        }
    }

}
