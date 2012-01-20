<?php

class SetProfilePictureToFacebookAction extends Action
{

    function execute()
    {
        if (!auth()->logged_in())
            show_404();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->set_to_facebook();

        flash::message('Set your profile pic to your Facebook one');

        redirect('profile/picture/edit');
    }

    /**
     * @return ProfilePicture|null
     */
    private function get_profile_picture()
    {
        return build('profile_picture', auth()->current_user());
    }

}
