<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session
{
  
}

class MO_Session
{
  
  /**
   * @var session
   */
  public $session;
  
  public function __construct($params = array()) {
    register_shutdown_function('session_write_close');
    
    $ci =& get_instance();
    $keys = array('name', 'match_ip', 'match_fingerprint', 'match_token', 'storage', 'table', 'expiration');
    foreach ($keys as $k) {
      $params[$k] = $ci->config->item("sess_$k");
    }
    $this->session = new session($params);
    
    $this->sweep_flashdata();
  }
  
  function userdata($item) {
    return isset($_SESSION[$item]) ? $_SESSION[$item] : FALSE;
  }
  
  function set_userdata($item, $value) {
    $_SESSION[$item] = $value;
  }
  
  function unset_userdata($item) {
    unset($_SESSION[$item]);
  }
  
  function set_flashdata($item, $value) {
    $_SESSION[$item] = $value;
    $this->keep_flashdata($item);
  }
  
  function flashdata($item) {
    return $this->userdata($item);
  }
  
  function keep_flashdata($item) {
    /**
     * Counter starts at 2 and gets decremente don each page request.
     * On the next request, the counter will be 1 and the data will be preserved.
     * On the request after that, the counter will be 0 and the data will be destroyed.
     */
    $_SESSION['_flashdata'][$item] = 2;
  }
  
  function sess_destroy() {
    $this->session->destroy();
  }
  
  private function sweep_flashdata() {
    $this->init_flashdata();
    
    foreach ($_SESSION['_flashdata'] as $item => &$status) {
      $status--;
      if ($status <= 0) {
        unset($_SESSION[$item]);
        unset($_SESSION['_flashdata'][$item]);
      }
    }
  }
  
  private function init_flashdata() {
    if ( !(isset($_SESSION['_flashdata']) && is_array($_SESSION['_flashdata'])) )
      $_SESSION['_flashdata'] = array();
  }
  
}

/**
 * Session Class
 *
 * Class for adding extra session security protection as well as new ways to
 * store sessions (such as databases).
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	Copyright (c) 2009 MicroMVC
 * @license		http://www.gnu.org/licenses/gpl-3.0.html
 * @link		http://micromvc.com
 * @version		1.1.0 <7/7/2009>
 ********************************** 80 Columns *********************************
 */
class session {
  public $name                  = 'mvcsession';                 //What should the session be called?
  public $match_ip        	= FALSE;			//Require user IP to match?
  public $match_fingerprint	= TRUE;				//Require user agent fingerprint to match?
  public $match_token           = FALSE;			//Require this token to match?
  public $storage               = 'db';                         //Where to store session data
  public $table                 = 'sessions';                   //If using a DB, what is the table name?
  public $id                    = NULL;				//Specify a custom ID to use instead of default cookie ID

  public $cookie_path		= NULL;				//Path to set in session_cookie
  public $cookie_domain		= NULL;				//The domain to set in session_cookie
  public $cookie_secure		= NULL;				//Should cookies only be sent over secure connections?
  public $cookie_httponly	= NULL;				//Only accessible through the HTTP protocol?

  public $regenerate		= 300;				//Update the session every five minutes
  public $expiration		= 7200;				//The session expires after 2 hours of non-use
  public $gc_probability	= 100;				//Chance (in 100) that old sessions will be removed


  /**
   * Configure some default session setting and then start the session.
   * @param	array	$config
   * @return	void
   */
  public function __construct($config = NULL) {

    //Set the config
    if(is_array($config)) {
      foreach($config as $key => $value) {
        $this->$key = $value;
      }
    }
    // Configure garbage collection
    ini_set('session.gc_probability', $this->gc_probability);
    ini_set('session.gc_divisor', 100);
    ini_set('session.gc_maxlifetime', $this->expiration);

    // Set the session cookie parameters
    session_set_cookie_params(
      $this->expiration + time(),
      $this->cookie_path,
      $this->cookie_domain,
      $this->cookie_secure,
      $this->cookie_httponly
    );

    // Name the session, this will also be the name of the cookie
    session_name($this->name);

    //If we were told to use a specific ID instead of what PHP might find
    if($this->id) {
      session_id($this->id);
    }

    //Create a session (or get existing session)
    $this->create();
  }


  /**
   * Start the current session, if already started - then destroy and create a new session!
   * @return void
   */
  function create() {

    //If this was called to destroy a session (only works after session started)
    $this->destroy();

    //If there is a class to handle CRUD of the sessions
    if($this->storage) {
      //Load the session handler class
      $handler = $this->create_handler($this->storage);

      //Set the expiration and table name for the model
      $handler->expiration = $this->expiration;
      $handler->table = $this->table;
      $handler->ip_address = $this->ip_address();
      $handler->last_activity = time();

      // Register non-native driver as the session handler
      session_set_save_handler (
        array($handler, 'open'),
        array($handler, 'close'),
        array($handler, 'read'),
        array($handler, 'write'),
        array($handler, 'destroy'),
        array($handler, 'gc')
      );
    }

    // Start the session!
    session_start();
    
    //Check the session to make sure it is valid
    if( ! $this->check() ) {
      //Destroy invalid session and create a new one
      return $this->create();
    }

  }


  /**
   * Check the current session to make sure the user is the same (or else create a new session)
   * @return unknown_type
   */
  function check() {

    //On creation store the useragent fingerprint
    if(empty($_SESSION['fingerprint'])) {
      $_SESSION['fingerprint'] = $this->generate_fingerprint();
    //If we should verify user agent fingerprints (and this one doesn't match!)
    }
    elseif($this->match_fingerprint && $_SESSION['fingerprint'] != $this->generate_fingerprint()) {
      return FALSE;
    }

    //If an IP address is present and we should check to see if it matches
    if(isset($_SESSION['ip_address']) && $this->match_ip) {
      //If the IP does NOT match
      if($_SESSION['ip_address'] != $this->ip_address()) {
        return FALSE; 
      }
    }

    //Set the users IP Address
    $_SESSION['ip_address'] = $this->ip_address();

    //If a token was given for this session to match
    if($this->match_token) {
      if(empty($_SESSION['token']) OR $_SESSION['token'] != $this->match_token) {
        //Remove token check
        $this->match_token = FALSE;
        return FALSE;
      }
    }

    //Set the session start time so we can track when to regenerate the session
    if(empty($_SESSION['regenerate'])) {
      $_SESSION['regenerate'] = time();
    //Check to see if the session needs to be regenerated
    } elseif($_SESSION['regenerate'] + $this->regenerate < time()) {
      //Generate a new session id and a new cookie with the updated id
      session_regenerate_id();
      //Store new time that the session was generated
      $_SESSION['regenerate'] = time();
    }

    return TRUE;
  }


  /**
   * Destroys the current session and user agent cookie
   * @return  void
   */
  public function destroy() {

    //If there is no session to delete (not started)
    if (session_id() === '') {
      return;
    }
    
    // Get the session name
    $name = session_name();
    $_SESSION = array();
    
    // Delete the session cookie (if exists)
    //Get the current cookie config
    $params = session_get_cookie_params();
    var_dump($params);

    // Delete the cookie from globals
    unset($_COOKIE[$name]);
    //Delete the cookie on the user_agent
    setcookie($name, '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    
    // Destroy the session
    session_destroy();
  }


  /**
   * Generates key as protection against Session Hijacking & Fixation. This
   * works better than IP based checking for most sites due to constant user
   * IP changes (although this method is not as secure as IP checks).
   * @return string
   */
  function generate_fingerprint()  {
    //We don't use the ip-address, because it is subject to change in most cases
    foreach(array('USER_AGENT') as $name) {
      $key[] = empty($_SERVER['HTTP_'. $name]) ? NULL : $_SERVER['HTTP_'. $name];
    }
    //Create an MD5 has and return it
    return md5(implode("\0", $key));
  }

  //source: http://stackoverflow.com/questions/6717926/function-to-get-user-ip-address
  function ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
          }
        }
      }
    }
  }
  
  private function is_ajax() {
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }
  
  private function create_handler($storage) {
    $class_name = "session_handler_$storage";
    return class_exists($class_name) ? new $class_name : FALSE;
  }
  
}


/**
 * Default session handler for storing sessions in the database. Can use
 * any type of database from SQLite to MySQL. If you wish to use your own
 * class instead of this one please set session::$session_handler to
 * the name of your class (see session class). If you wish to use memcache
 * then then set the session::$session_handler to FALSE and configure the
 * settings shown in http://php.net/manual/en/memcache.examples-overview.php
 */
class session_handler_db {
  
  //Store the starting session ID so we can check against current id at close
  public $session_id = NULL;
  //Table to look for session data in
  public $table	= NULL;
  // How long are sessions good?
  public $expiration = NULL;
  public $ip_address = NULL;
  public $last_activity = NULL;

  /**
   * Record the current sesion_id for later
   * @return boolean
   */
  public function open() {
    //Store the current ID so if it is changed we will know!
    $this->id = session_id();
    return TRUE;
  }


  /**
   * Superfluous close function
   * @return boolean
   */
  public function close() {
    return TRUE;
  }


  /**
   * Attempt to read a session from the database.
   * @param	string	$id
   */
  public function read($id = NULL) {
    //Select the session
    $results = ci()->db->select('user_data')
                       ->from($this->table)
                       ->where('session_id', $id)
                       ->get()->result();
    return empty($results) ? '' : $results[0]->user_data;
  }


  /**
   * Attempt to create or update a session in the database.
   * The $data is already serialized by PHP.
   *
   * @param	string	$id
   * @param	string 	$data
   */
  public function write($id = NULL, $data = '') {
    /*
     * Case 1: The session we are now being told to write does not match
     * the session we were given at the start. This means that the ID was
     * regenerated sometime durring the script and we need to update that
     * old session id to this new value. The other choice is to delete
     * the old session first - but that wastes resources.
     */
    //If the session was not empty at start && regenerated sometime during the page
    if($this->id && $this->id != $id) {
      //Update the data and new session_id
      $this->session_update($this->id, array(
        'session_id' => $id,
        'user_data' => $data,
      ));
      return;
    }
    
    /*
     * Case 2: We check to see if the session already exists. If it does
     * then we need to update it. If not, then we create a new entry.
     */
    if($this->session_exists($id)) {
      $this->session_update($id, array('user_data' => $data, 'debug' => "$id exists"));
    } else {
      $this->session_insert(array(
        'user_data' => $data,
        'session_id' => $id,
        'ip_address' => $this->ip_address,
        'last_activity' => $this->last_activity,
        'debug' => "$id doesnt exist " . $this->is_ajax() ? "AJAX " . uri_string() : "NAHHH",
      ));
    }

  }

  private function session_exists($session_id) {
    return $this->db()->from($this->table)
                      ->where('session_id', $session_id)
                      ->count_all_results() > 0;
  }
  
  private function session_insert($row) {
    $this->db()->insert($this->table, $row);
  }
  
  private function session_update($session_id, $row) {
    $this->db()->where('session_id', $session_id)->update($this->table, $row);
  }
  
  private function db() {
    return $this->ci()->db;
  }
  
  private function ci() {
    return get_instance();
  }

  /**
   * Delete a session from the database
   * @param	string	$id
   * @return	boolean
   */
  public function destroy($id) {
    ci()->db->delete($this->table, array('session_id' => $id));
    return TRUE;
  }


  /**
   * Garbage collector method to remove old sessions
   */
  public function gc() {
    //The max age of a session
    $time = date('Y-m-d H:i:s', time() - $this->expiration);
    //Remove all old sessions
    ci()->db->delete($this->table, array('last_activity < ' => $time));
    return TRUE;
  }
  
    
  private function is_ajax() {
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }
  
}

