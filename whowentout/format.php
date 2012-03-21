<?php

class format
{


    // you => him/her
    static $words = array(
        "hasn't" => "haven't",
        "has" => "have",
        "his" => "your",
        "her" => "your",
        "she" => "you",
        "he" => "you",
        "is" => "are",
    );

    public static function date($date)
    {
        if ($date instanceof DateTime)
            return $date->format('m/d, g:ia');
        else
            return '';
    }

    public static function full_name($user)
    {
        return $user->first_name . ' ' . $user->last_name;
    }

    public static function first_name($user)
    {
        return static::is_you($user) ? 'you' : $user->first_name;
    }

    public static function pov($word, $user)
    {
        $is_you = static::is_you($user);
        $word = Inflect::genderize($user->gender, $word);

        foreach (static::$words as $he_pov => $you_pov) {
            if ($he_pov == $word || $you_pov == $word)
                return $is_you ? $you_pov : $he_pov;
        }

        return null;
    }

    public static function owner($user)
    {
        return static::is_you($user) ? 'your' : $user->first_name . "'s";
    }

    public static function is_you($user)
    {
        return auth()->current_user() == $user;
    }

    public static function night_of(DateTime $date)
    {
        $today = app()->clock()->today();
        if ($today == $date)
            return 'tonight';
        else
            return $date->format('l') . ' night';
    }

    public static function people($users, $limit = 1)
    {
        $names = array();

        foreach ($users as $user) {
            $names[] = "$user->first_name $user->last_name";
        }

        $visible_names = array_slice($names, 0, $limit);
        $hidden_names = array_slice($names, $limit);

        if (count($hidden_names) > 0)
            $visible_names[] = static::bunch_names($hidden_names);

        return conjunct($visible_names);
    }

    private static function bunch_names($names)
    {
        if (count($names) == 1)
            return array_pop($names);

        return Inflect::pluralize_if(count($names), 'friend');
    }

    public static function truncate($string, $max_length = 12)
    {
        return strlen($string) > $max_length
                               ? substr($string, 0, $max_length) . '...'
                               : $string;
    }

}
