<?php

class Picture extends MY_Controller
{

    function auth()
    {
        include 'flikr/getToken.php';
    }

    function test()
    {
        $data = array();

        if (isset($_FILES['pic'])) {
            $pic = XPicture::createFromUpload('pic');
            $data['pic'] = $pic;
        }

        print r('page', array(
                          'page_content' => r('upload_picture_form', $data),
                        ));
    }

    function view()
    {
        
    }

    function create()
    {
        $picture = XPicture::createFromUpload('pic');
        $this->json_for_ajax_file_upload(array(
                                              'success' => TRUE,
                                              'thumbnail_url' => $picture->url('thumbnail'),
                                         ));
    }

    function delete()
    {
        $picture_id = post('picture_id');
        $picture = XPicture::get($picture_id);

        if ($picture) {
            $picture->delete();
        }

        $this->json_success("Successfully deleted picture");
    }

}
