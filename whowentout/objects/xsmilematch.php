<?php

class XSmileMatch extends XObject
{

    protected static $table = 'smile_matches';

    function get_first_smile()
    {
        return smile($this->first_smile_id);
    }

    function get_second_smile()
    {
        return smile($this->second_smile_id);
    }

    function get_first_user()
    {
        return user($this->first_user_id);
    }

    function get_second_user()
    {
        return user($this->second_user_id);
    }

    function get_this_user()
    {
        if ($this->first_user == current_user())
            return $this->first_user;
        else if ($this->second_user == current_user())
            return $this->second_user;
        else
            return FALSE;
    }

    function get_other_user()
    {
        if ($this->first_user == current_user())
            return $this->second_user;
        else if ($this->second_user == current_user())
            return $this->first_user;
        else
            return FALSE;
    }

}
