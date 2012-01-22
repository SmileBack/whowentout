<?php

class CropProfilePictureAction extends Action
{

    function execute()
    {
        if (!auth()->logged_in())
            show_404();

        $current_user = auth()->current_user();

        $profile_picture = $this->get_profile_picture();
        $profile_picture->crop($_POST['x'], $_POST['y'], $_POST['width'], $_POST['height']);

        flash::message('Cropped your profile pic');

        if (flow::get() instanceof CheckinFlow) {
            $event_id = flow::get()->event_id;
            redirect("events/$event_id/deal");
        }
        elseif (flow::get() instanceof DealDialogFlow) {
            $event_id = flow::get()->event_id;
            redirect("events/$event_id/deal");
        }
        else {
            redirect("profile/$current_user->id");
        }
    }

    /**
     * @return ProfilePicture|null
     */
    private function get_profile_picture()
    {
        return build('profile_picture', auth()->current_user());
    }

}
