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

    function upload($gallery_id)
    {
        $data = array();

        $gallery = XGallery::get($gallery_id);

        if (isset($_FILES['pic']) && $gallery) {
            $pic = XPicture::createFromUpload('pic');
            $gallery->add_picture($pic);
            $data['pic'] = $pic;
        }

        redirect("gallery/view/$gallery_id");
    }

    function view($gallery_id)
    {
        $gallery = XGallery::get($gallery_id);
        if ($gallery) {
            print r('page', array(
                                 'page_content' => r('gallery_pictures', array(
                                                                              'gallery' => $gallery,
                                                                         ))
                            ));
        }
    }


}

