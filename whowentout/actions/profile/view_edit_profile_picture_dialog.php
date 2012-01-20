<?php

class ViewEditProfilePictureDialog extends Action
{

    function execute()
    {
        if (!auth()->logged_in())
            show_404();

        print r::profile_edit_picture(array(
            'profile_picture' => $this->get_profile_picture(),
        ));
    }

    /**
     * @return ProfilePicture|null
     */
    private function get_profile_picture()
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        $profile_picture = build('profile_picture', $current_user);
        return $profile_picture;
    }

}
