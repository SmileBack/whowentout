<?php

class Profile_Controller extends Controller
{


    function dialog($type)
    {
        if (!auth()->logged_in())
            show_404();

        if ($type == 'picture') {
            print r::profile_edit_picture(array(
                'profile_picture' => $this->get_profile_picture(),
            ));
        }
    }

    function set_to_facebook()
    {
        if (!auth()->logged_in())
            show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_facebook();

        flash::message('Set your profile pic to your Facebook one');

        redirect('profile/edit/picture');
    }

    function set_to_upload()
    {
        if (!auth()->logged_in())
            show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_upload('pic');

        flash::message('Uploaded a profile pic');

        redirect('profile/edit/picture');
    }

    function crop()
    {
        if (!auth()->logged_in())
            show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->crop($_POST['x'], $_POST['y'], $_POST['width'], $_POST['height']);

        flash::message('Cropped your profile pic');

        redirect('profile/view/me');
    }


}
