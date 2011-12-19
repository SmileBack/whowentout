<?php

abstract class Auth
{



    abstract function current_user();

    /**
     * @abstract
     * @return void
     */
    abstract function get_login_url();
    
    abstract function get_logout_url();
    
    /**
     * @abstract
     * @return void
     */
    abstract function logout();

    /**
     * @return bool
     */
    abstract function logged_in();

    function get_login_link($attributes = array())
    {
        if ($this->logged_in()) {
            $url = $this->get_logout_url();
            $title = 'Logout';
        }
        else {
            $url = $this->get_login_url();
            $title = 'Login';
        }
        
        $attributes['href'] = $url;
        return html_element('a', $attributes, $title);
    }

}
