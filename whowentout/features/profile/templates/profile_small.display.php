<?php

class Profile_Small_Display extends Display
{

    protected $defaults = array(
        'hidden' => false,
        'show_networks' => false,
        'link_to_profile' => false,
        'preset' => 'thumb',
    );

    function process()
    {
        /* @var $profile_picture ProfilePicture */
        $profile_picture = build('profile_picture', $this->user);
        $this->profile_picture_url = $profile_picture->url($this->preset);
    }

}
