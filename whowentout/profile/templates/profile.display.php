<?php

class Profile_Display extends Display
{

    function process()
    {
        $this->dob = $this->user->date_of_birth;

        /* @var $profile_picture ProfilePicture */
        $this->profile_picture = build('profile_picture', $this->user);
        $this->profile_picture_url = $this->profile_picture->url('thumb');

        /* @var $friends_calc MutualFriendsCalculator */
        $friends_calc = build('mutual_friends_calculator');
        $this->mutual_friends = $friends_calc->compute($this->current_user->id, $this->user->id);

        /* @var $entourage_calc EntourageCalculator */
        $entourage_calc = build('entourage_calculator');
        $this->entourage = $entourage_calc->compute($this->user->id);

        $this->your_profile = ($this->user == $this->current_user);
    }

}
