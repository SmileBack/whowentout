<?php

class Profile_Controller extends Controller
{

    function edit()
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        print r::page(array(
            'content' => r::profile(array(
                'user' => $current_user,
            )),
        ));
    }

    function set_to_facebook()
    {
        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_facebook();

        flash::message('Set your profile pic to your Facebook one');

        redirect('profile/edit');
    }

    function set_to_upload()
    {
        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_upload('pic');

        flash::message('Uploaded a profile pic');

        redirect('profile/edit');
    }

    function crop()
    {
        $profile_picture = $this->get_profile_picture();
        $profile_picture->crop($_POST['x'], $_POST['y'], $_POST['width'], $_POST['height']);

        flash::message('Cropped your profile pic');

        redirect('profile/edit');
    }

    /**
     * @return ProfilePicture|null
     */
    private function get_profile_picture()
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        $profile_picture = factory()->build('profile_picture', $current_user);
        return $profile_picture;
    }

}
