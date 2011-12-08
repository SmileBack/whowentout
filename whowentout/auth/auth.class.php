<?php

abstract class Auth
{
    
    abstract function current_user();

    /**
     * @abstract
     * @return void
     */
    abstract function get_login_url();

    /**
     * @abstract
     * @return void
     */
    abstract function logout();

    /**
     * @return bool
     */
    abstract function logged_in();

}
