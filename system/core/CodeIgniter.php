<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * System Initialization File
 *
 * Loads the base classes and executes the request.
 *
 * @package        CodeIgniter
 * @subpackage    codeigniter
 * @category    Front-controller
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/
 */

/*
 * ------------------------------------------------------
 *  Define the CodeIgniter Version
 * ------------------------------------------------------
 */
define('CI_VERSION', '2.0.2');

/*
 * ------------------------------------------------------
 *  Define the CodeIgniter Branch (Core = TRUE, Reactor = FALSE)
 * ------------------------------------------------------
 */
define('CI_CORE', FALSE);

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require(BASEPATH . 'core/Common' . EXT);

/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
if (defined('ENVIRONMENT') AND file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants' . EXT)) {
    require(APPPATH . 'config/' . ENVIRONMENT . '/constants' . EXT);
}
else
{
    require(APPPATH . 'config/constants' . EXT);
}

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_exception_handler');

if (!is_php('5.3')) {
    @set_magic_quotes_runtime(0); // Kill magic quotes
}

/*
 * ------------------------------------------------------
 *  Set the subclass_prefix
 * ------------------------------------------------------
 *
 * Normally the "subclass_prefix" is set in the config file.
 * The subclass prefix allows CI to know if a core class is
 * being extended via a library in the local application
 * "libraries" folder. Since CI allows config items to be
 * overriden via data set in the main index. php file,
 * before proceeding we need to know if a subclass_prefix
 * override exists.  If so, we will set this value now,
 * before any classes are loaded
 * Note: Since the config file data is cached it doesn't
 * hurt to load it here.
 */
if (isset($assign_to_config['subclass_prefix']) AND $assign_to_config['subclass_prefix'] != '') {
    get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
}

/*
 * ------------------------------------------------------
 *  Set a liberal script execution time limit
 * ------------------------------------------------------
 */
if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
    @set_time_limit(300);
}

/*
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */
$BM =& load_class('Benchmark', 'core');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time:_base_classes_start');

/*
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */
$EXT =& load_class('Hooks', 'core');

/*
 * ------------------------------------------------------
 *  Is there a "pre_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  Instantiate the config class
 * ------------------------------------------------------
 */
$CFG =& load_class('Config', 'core');

// Do we have any manually set config items in the index.php file?
if (isset($assign_to_config)) {
    $CFG->_assign_to_config($assign_to_config);
}

/*
 * ------------------------------------------------------
 *  Instantiate the UTF-8 class
 * ------------------------------------------------------
 *
 * Note: Order here is rather important as the UTF-8
 * class needs to be used very early on, but it cannot
 * properly determine if UTf-8 can be supported until
 * after the Config class is instantiated.
 *
 */

$UNI =& load_class('Utf8', 'core');

/*
 * ------------------------------------------------------
 *  Instantiate the URI class
 * ------------------------------------------------------
 */
$URI =& load_class('URI', 'core');

/*
 * ------------------------------------------------------
 *  Instantiate the output class
 * ------------------------------------------------------
 */
$OUT =& load_class('Output', 'core');

/*
 * ------------------------------------------------------
 *	Is there a valid cache file?  If so, we're done...
 * ------------------------------------------------------
 */
if ($EXT->_call_hook('cache_override') === FALSE) {
    if ($OUT->_display_cache($CFG, $URI) == TRUE) {
        exit;
    }
}

/*
 * -----------------------------------------------------
 * Load the security class for xss and csrf support
 * -----------------------------------------------------
 */
$SEC =& load_class('Security', 'core');

/*
 * ------------------------------------------------------
 *  Load the Input class and sanitize globals
 * ------------------------------------------------------
 */
$IN =& load_class('Input', 'core');

/*
 * ------------------------------------------------------
 *  Load the Language class
 * ------------------------------------------------------
 */
$LANG =& load_class('Lang', 'core');

/*
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 *
 */
// Load the base controller class
require BASEPATH . 'core/Controller' . EXT;

function &get_instance()
{
    return CI_Controller::get_instance();
}

// Set a mark point for benchmarking
$BM->mark('loading_time:_base_classes_end');

/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_controller');

// TODO: get class and method
$RTR = new FireRouter();
$RTR->determine_route();

$class = $RTR->get_class();
$method = $RTR->get_method();

/*
 * ------------------------------------------------------
 *  Instantiate the requested controller
 * ------------------------------------------------------
 */
// Mark a start point so we can benchmark the controller
$BM->mark('controller_execution_time_( ' . $class . ' / ' . $method . ' )_start');

// TODO: initialize controller
if ($RTR->is_valid_request()) {
    unset($CI);
    $CI = new $class;

}

/*
 * ------------------------------------------------------
 *  Is there a "post_controller_constructor" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_controller_constructor');

if ( $RTR->is_valid_request() && is_callable(array($CI, $method)) ) {
    call_user_func_array(array(&$CI, $method), array_slice($URI->rsegments, 2));
}
else {
    show_404($RTR->get_request());
}

// Mark a benchmark end point
$BM->mark('controller_execution_time_( ' . $class . ' / ' . $method . ' )_end');

/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
if ($EXT->_call_hook('display_override') === FALSE) {
    $OUT->_display();
}

/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_system');

/*
 * ------------------------------------------------------
 *  Close the DB connection if one exists
 * ------------------------------------------------------
 */
if (class_exists('CI_DB') AND isset($CI->db)) {
    $CI->db->close();
}


/* End of file CodeIgniter.php */
/* Location: ./system/core/CodeIgniter.php */