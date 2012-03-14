<?php

class to
{

    static function event($value)
    {
        return static::row('events', $value);
    }

    static function user($value)
    {
        return static::row('users', $value);
    }

    static function place($value)
    {
        return static::row('places', $value);
    }

    static function json($object)
    {
        if ($object instanceof DatabaseRow && $object->table()->name() == 'users') {
            return static::json_user($object);
        }
        elseif ($object instanceof DatabaseRow && $object->table()->name() == 'event') {
            return array(
                'id' => $object->id,
                'name' => $object->name,
                'date' => $object->date,
            );
        }
    }

    static function json_user($object)
    {
        /* @var $profile_picture ProfilePicture */
        $profile_picture = build('profile_picture', $object);
        $profile_picture->url('normal');
        return array(
            'id' => $object->id,
            'first_name' => $object->first_name,
            'last_name' => $object->last_name,
            'picture' => array(
                'normal' => $profile_picture->url('normal'),
                'thumb' => $profile_picture->url('thumb'),
                'square' => $profile_picture->url('facebook.square'),
            ),
        );
    }

    private static function row($table, $value)
    {
        if (is_int($value) || is_string($value))
            return db()->table($table)->row($value);
        elseif ($value instanceof DatabaseRow)
            return $value;
        else
            return null;
    }

}

