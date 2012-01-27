<?php

class CropProfilePictureAction extends Action
{

    function execute()
    {
        if (!auth()->logged_in())
            show_404();

        $profile_picture = $this->get_profile_picture();

        try {
            $profile_picture->crop($_POST['x'], $_POST['y'], $_POST['width'], $_POST['height']);
            flash::message('Cropped your profile pic');
        }
        catch (CropOutOfBoundsException $e) {
            flash::error("Invalid pic boundaries.");
        }

        $this->goto_destination();
    }

    private function goto_destination()
    {
        $current_user = auth()->current_user();
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
