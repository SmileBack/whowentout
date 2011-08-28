<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Migrations
 *
 * An open source utility for CodeIgniter inspired by Ruby on Rails
 *
 * @package	Migrations
 * @author	Mat'as Montes
 *
 * Rewritten by:
 *
 * 	Phil Sturgeon
 *	http://philsturgeon.co.uk/
 *
 *  and
 *
 * 	Spicer Matthews <spicer@cloudmanic.com>
 * 	Cloudmanic Labs, LLC
 *	http://www.cloudmanic.com/
 *
 */

// ------------------------------------------------------------------------

/**
 * Migrations Package
 *
 * Minor modifications and structural changes to make into a package for use in
 * CodeIgniter v2.0.
 *
 * @author	John Snyder
 */

// ------------------------------------------------------------------------

/*
|--------------------------------------------------------------------------
| Enable/Disable Migrations
|--------------------------------------------------------------------------
|
| Migrations are disabled by default for security reasons.
| You should enable migrations whenever you intend to do a schema migration
| and disable it back when you're done.
|
| Some more severe security measures might take place in future releases.
|
*/
$config['migrations_enabled'] = TRUE;


/*
|--------------------------------------------------------------------------
| Migrations version
|--------------------------------------------------------------------------
|
| This is used to set the default migration for this code base.
| Sometimes you want the system to automaticly migrate the database
| to the most current migration. Or there might be higher migrations
| that are not part of the migration-> env. Setting the migration does
| does nothing here. It is a way for a programer to check the config.
|
| On login you might want to do something like this
| $this->migration->version($this->config->item('migrations_version'));
|
*/
$config['migrations_version'] = 1;


/*
|--------------------------------------------------------------------------
| Migrations Path
|--------------------------------------------------------------------------
|
| Path to your migrations folder.
| Typically, it will be within your application path.
| Also, writing permission is required within the migrations path.
|
*/
$config['migrations_path'] = APPPATH . 'migrations/';


/*
|--------------------------------------------------------------------------
| Migrations database table name
|--------------------------------------------------------------------------
|
| Define the table name for the migrations installation.
|
*/
$config['migrations_table'] = 'schema_version';

// END Migrations config file

/* End of file migrations.php */
/* Location: ./packages/migrations/config/migrations.php */