<?php

class FacebookAuth extends Auth
{

    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var array
     */
    private $facebook_permissions;

    function __construct(Facebook $facebook, $facebook_permissions = array())
    {
        $this->facebook = $facebook;
        $this->facebook_permissions = $facebook_permissions;
    }

    function current_user()
    {
        return $this->facebook->getUser();
    }

    function create_user()
    {
        
    }

    function logged_in()
    {
        return $this->facebook->getUser() != null;
    }

    function get_login_url()
    {
        return $this->facebook->getLoginUrl(array(
                                                'scope' => implode(',', $this->facebook_permissions),
                                            ));
    }

    function logout()
    {
        //destroy facebook session data
        $app_id = $this->facebook->getAppId();
        $session_vars = array('code', 'access_token', 'user_id', 'state');
        foreach ($session_vars as $var) {
            unset($_SESSION["fb_{$app_id}_{$var}"]);
        }
    }

}
