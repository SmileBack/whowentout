<?php

class Gallery extends MY_Controller
{

    function create()
    {
        $gallery = XGallery::create(array(
                                         'name' => 'gallery',
                                    ));
        redirect("gallery/view/{$gallery->id}");
    }

    function view($gallery_id)
    {
        $gallery = new FlickrGallery('72157628067656136');
        if ($gallery) {
            print r('page', array(
                                 'page_content' => r('pictures', array(
                                                                      'gallery' => $gallery,
                                                                 ))
                            ));
        }
    }


}

