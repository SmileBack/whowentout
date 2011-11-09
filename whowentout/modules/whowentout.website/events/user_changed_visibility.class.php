<?php

class User_Changed_Visibility_Event extends Event
{
    /* @var $user XUser */
    public $user;

    /* @var $visibility string */
    public $visibility;
}
