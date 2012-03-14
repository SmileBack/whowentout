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

    private $admin_facebook_ids = array('776200121', '8100231', '1185700827', '507980445');

    /**
     * @var array
     */
    private $facebook_permissions;

    function __construct(Facebook $facebook, Database $database, $facebook_permissions = array())
    {
        $this->facebook = $facebook;
        $this->database = $database;
        $this->facebook_permissions = $facebook_permissions;

        if ($this->logged_in() && $this->has_invalid_access_token())
            $this->redirect_to_login();
    }

    function current_user()
    {
        $facebook_id = $this->get_logged_in_facebook_id();

        if (!$facebook_id)
            return null;

        $user = $this->database->table('users')
                               ->where('facebook_id', $facebook_id)
                               ->first();

        if (!$user) {
            $user = $this->create_current_user();
        }

        return $user;
    }

    function is_admin()
    {
        return $this->logged_in()
                && in_array($this->current_user()->facebook_id, $this->admin_facebook_ids);
    }

    function require_admin()
    {
        if (!$this->is_admin())
            throw new Exception("You must be an admin");
    }

    function has_invalid_access_token()
    {
        $invalid = false;
        try {
            $result = $this->facebook->api('/me');
        }
        catch (Exception $e) {
            $invalid = true;
        }
        return $invalid;
    }

    function redirect_to_login()
    {
        $url = $this->get_login_url();
        header("Location: $url");
    }

    /**
     * @return DatabaseRow
     */
    function create_current_user()
    {
        $facebook_id = $this->facebook->getUser();
        $profile_source = new FacebookProfileSource($this->facebook);
        $profile = $profile_source->fetch_profile($facebook_id);
        $user = $this->database->table('users')->create_row(array(
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'gender' => $profile->gender,
            'email' => $profile->email,
            'facebook_id' => $profile->id,
            'date_of_birth' => $profile->birthday,
            'hometown' => $profile->hometown,
        ));

        $this->create_user_profile_pic($user);
        $this->update_facebook_networks($user, $profile->networks);

        return $user;
    }

    /**
     * @param  $user DatabaseTable
     * @param  $networks FacebookNetwork[]
     * @return void
     */
    function update_facebook_networks($user, $networks)
    {
        $this->database->execute('DELETE FROM user_networks WHERE user_id = :id', array(
            'id' => $user->id,
        ));
        foreach ($networks as $network) {
            $this->database->table('networks')->create_or_update_row(array(
                'id' => $network->id,
                'type' => $network->type,
                'name' => $network->name,
            ));

            $this->database->table('user_networks')->create_row(array(
                'user_id' => $user->id,
                'network_id' => $network->id,
            ));
        }
    }

    /**
     * @param  $user
     * @return ProfilePicture
     */
    private function create_user_profile_pic($user)
    {
        /* @var $profile_picture ProfilePicture */
        $profile_picture = build('profile_picture', $user);
        $profile_picture->set_to_facebook();
        return $profile_picture;
    }

    function logged_in()
    {
        return $this->get_logged_in_facebook_id() != null;
    }

    function get_logout_url()
    {
        return url('logout');
    }

    function get_login_url()
    {
        $url = $this->facebook->getLoginUrl(array(
            'redirect_uri' => site_url('login/complete'),
            'scope' => implode(',', $this->facebook_permissions),
        ));

        if (browser::is_mobile())
            $url .= '&display=touch';

        return $url;
    }

    function get_logged_in_facebook_id()
    {
        if (isset($_SESSION['fb_user_id'])) {
            return $_SESSION['fb_user_id'];
        }
        else {
            return $this->facebook->getUser();
        }
    }

    function login_as($user_id)
    {
        $facebook_id = $this->database->table('users')->row($user_id)->facebook_id;
        $_SESSION['fb_user_id'] = $facebook_id;
    }

    function logout()
    {
        //destroy facebook session data
        $app_id = $this->facebook->getAppId();
        $session_vars = array('code', 'access_token', 'user_id', 'state');
        foreach ($session_vars as $var) {
            unset($_SESSION["fb_{$app_id}_{$var}"]);
        }
        unset($_SESSION['fb_user_id']);
    }

}
