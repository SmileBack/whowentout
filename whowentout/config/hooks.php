<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'][] = array(
    'function' => 'load_core',
    'filename' => 'load.php',
    'filepath' => 'hooks',
);

$hook['pre_system'][] = array(
    'function' => 'boot_fire',
    'filename' => 'boot_fire.php',
    'filepath' => 'hooks',
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */