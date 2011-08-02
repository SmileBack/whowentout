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
| Migrations language file
|--------------------------------------------------------------------------
*/

// Error message when migrations access but not enabled
$lang['migrations_not_enabled'] = 'Access to this controller is blocked, turn me on when you need me.';

// __get() error message
$lang['migrations_class_property_not_found'] = 'The requested method or property could not be found.';

// Error message for invalid library setup
$lang['migrations_invalid_setup'] = 'Migrations has been loaded but is disabled or set up incorrectly.';

// Error message for invalid migrations path
$lang['migrations_invalid_path'] = 'Invalid path to the migrations directory.';

// Error message when no migration files could be found
$lang['migrations_multiple_version'] = 'Only 1 migration step maybe performed at a time, skipped migration: %s.';

// Error message when a migration file wasn't found
$lang['migrations_migration_not_found'] = 'The requested migration: %s was not found.';

// Error message when a migration has the same name as another
$lang['migrations_multiple_names'] = 'Migrations cannot have duplicate names migration: %s skipped.';

// Error message when a migration file doesn't contain a class with the proper name
$lang['migrations_class_doesnt_exist'] = 'The migration class: %s was not found.';

// Error message when the up or down method is missing from the migration class
$lang['migrations_missing_method'] = 'The up or down method is missing from this migration: %s';

// END Migrations language file

/* End of file migrations_lang.php */
/* Location: ./third_party/migrations/language/english/migrations_lang.php */