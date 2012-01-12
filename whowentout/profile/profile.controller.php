<?php

class Profile_Controller extends Controller
{

    function view($user_id)
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        if ($user_id == 'me')
            $user_id = $current_user->id;
        elseif ($user_id == $current_user->id)
            redirect("profile/view/me");

        $user = db()->table('users')->row($user_id);

        print r::page(array(
            'content' => r::profile(array(
                'user' => $user,
                'current_user' => $current_user,
            )),
        ));
    }

    function edit()
    {
        if (!auth()->logged_in())
                    show_404();

        print r::profile_edit_picture(array(
            'profile_picture' => $this->get_profile_picture(),
        ));
    }

    function set_to_facebook()
    {
        if (!auth()->logged_in())
                    show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_facebook();

        flash::message('Set your profile pic to your Facebook one');

        redirect('profile/view');
    }

    function set_to_upload()
    {
        if (!auth()->logged_in())
                    show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_upload('pic');

        flash::message('Uploaded a profile pic');

        redirect('profile/view');
    }

    function crop()
    {
        if (!auth()->logged_in())
                    show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->crop($_POST['x'], $_POST['y'], $_POST['width'], $_POST['height']);

        flash::message('Cropped your profile pic');

        redirect('profile/view');
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
