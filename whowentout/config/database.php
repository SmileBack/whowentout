<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = ENVIRONMENT;
$active_record = TRUE;

$db['development']['hostname'] = 'localhost';
$db['development']['username'] = 'root';
$db['development']['password'] = 'root';
$db['development']['database'] = 'whowentout';
$db['development']['dbdriver'] = 'mysql';
$db['development']['dbprefix'] = '';
$db['development']['pconnect'] = TRUE;
$db['development']['db_debug'] = TRUE;
$db['development']['cache_on'] = FALSE;
$db['development']['cachedir'] = '';
$db['development']['char_set'] = 'utf8';
$db['development']['dbcollat'] = 'utf8_general_ci';
$db['development']['swap_pre'] = '';
$db['development']['autoinit'] = TRUE;
$db['development']['stricton'] = FALSE;

$db['test']['hostname'] = 'localhost';
$db['test']['username'] = 'root';
$db['test']['password'] = 'root';
$db['test']['database'] = 'whowentout_test';
$db['test']['dbdriver'] = 'mysql';
$db['test']['dbprefix'] = '';
$db['test']['pconnect'] = TRUE;
$db['test']['db_debug'] = TRUE;
$db['test']['cache_on'] = FALSE;
$db['test']['cachedir'] = '';
$db['test']['char_set'] = 'utf8';
$db['test']['dbcollat'] = 'utf8_general_ci';
$db['test']['swap_pre'] = '';
$db['test']['autoinit'] = TRUE;
$db['test']['stricton'] = FALSE;

$db['phpfog']['hostname'] = 'db01-share';
$db['phpfog']['username'] = 'CodeIgniter-8910';
$db['phpfog']['password'] = 'MySQL4668';
$db['phpfog']['database'] = 'whowentout-com';
$db['phpfog']['dbdriver'] = 'mysql';
$db['phpfog']['dbprefix'] = '';
$db['phpfog']['pconnect'] = TRUE;
$db['phpfog']['db_debug'] = TRUE;
$db['phpfog']['cache_on'] = FALSE;
$db['phpfog']['cachedir'] = '';
$db['phpfog']['char_set'] = 'utf8';
$db['phpfog']['dbcollat'] = 'utf8_general_ci';
$db['phpfog']['swap_pre'] = '';
$db['phpfog']['autoinit'] = TRUE;
$db['phpfog']['stricton'] = FALSE;

$db['whowasout']['hostname'] = 'db01-share';
$db['whowasout']['username'] = 'CodeIgnite-13568';
$db['whowasout']['password'] = 'MySQL4668';
$db['whowasout']['database'] = 'whowentout-com';
$db['whowasout']['dbdriver'] = 'mysql';
$db['whowasout']['dbprefix'] = '';
$db['whowasout']['pconnect'] = TRUE;
$db['whowasout']['db_debug'] = TRUE;
$db['whowasout']['cache_on'] = FALSE;
$db['whowasout']['cachedir'] = '';
$db['whowasout']['char_set'] = 'utf8';
$db['whowasout']['dbcollat'] = 'utf8_general_ci';
$db['whowasout']['swap_pre'] = '';
$db['whowasout']['autoinit'] = TRUE;
$db['whowasout']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */