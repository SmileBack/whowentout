<?php

class Profile_Display extends Display
{

    /* @var $entourage_engine EntourageEngine */
    private $entourage_engine;

    function process()
    {
        $this->dob = $this->user->date_of_birth;

        /* @var $profile_picture ProfilePicture */
        $this->profile_picture = build('profile_picture', $this->user);
        $this->profile_picture_url = $this->profile_picture->url('thumb');

        $this->your_profile = ($this->user == $this->current_user);
        
        if (!$this->your_profile)
            $this->mutual_friends = $this->compute_mutual_friends();
    }

    function compute_mutual_friends()
    {
        /* @var $mutual_friends_calculator MutualFriendsCalculator */
        $mutual_friends_calculator = build('mutual_friends_calculator');
        return $mutual_friends_calculator->compute($this->current_user->id, $this->user->id);
    }

}
