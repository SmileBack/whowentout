<?php

class FacebookAuth extends Auth
{

    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var Database
     */
    private $database;

    /**
     * @var array
     */
    private $facebook_permissions;

    function __construct(Facebook $facebook, Database $database, $facebook_permissions = array())
    {
        $this->facebook = $facebook;
        $this->database = $database;
        $this->facebook_permissions = $facebook_permissions;
    }

    function current_user()
    {
        $facebook_id = $this->facebook->getUser();
        return $this->database->table('users')
                              ->where('facebook_id', $facebook_id)
                              ->first();
    }

    /**
     * @return DatabaseRow
     */
    function create_user()
    {
        
        $facebook_id = $this->facebook->getUser();
        $profile_source = new FacebookProfileSource($this->facebook, $facebook_id);
        $row = $this->database->table('users')->create_row(array(
                                                                'first_name' => $profile_source->get_first_name(),
                                                                'last_name' => $profile_source->get_last_name(),
                                                                'email' => $profile_source->get_email(),
                                                           ));
        return $row;
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
