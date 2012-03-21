<?php

class Profile_Small_Display extends Display
{

    protected $defaults = array(
        'hidden' => false,
        'show_networks' => false,
        'link_to_profile' => false,
        'preset' => 'thumb',
        'badge' => false,
        'defer_load' => false,
        'class' => '',
        'version' => 1,
    );

    function process()
    {
        if ($this->defer_load) {
            $this->profile_picture_url = null;
        }
        else {
            benchmark::start('profile_picture_url');
            /* @var $profile_picture ProfilePicture */
            $profile_picture = build('profile_picture', $this->user);
            $this->profile_picture_url = $profile_picture->url($this->preset);
            benchmark::end('profile_picture_url');
        }
    }

}
