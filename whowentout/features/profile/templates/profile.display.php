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

        /* @var $friends_calc MutualFriendsCalculator */
        $friends_calc = build('mutual_friends_calculator');
        $this->mutual_friends = $friends_calc->compute($this->current_user->id, $this->user->id);

        $this->entourage_engine = build('entourage_engine');

        $this->entourage_request_count = $this->entourage_engine->get_pending_request_count($this->user);
        $this->entourage_count = $this->entourage_engine->get_entourage_count($this->user);

        $this->your_profile = ($this->user == $this->current_user);
    }

}
