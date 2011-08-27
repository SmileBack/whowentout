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

/**
 * Migration Abstract
 *
 * All migrations should extend this, forces up() and down()
 *
 * @package		Migrations
 * @author		Phil Sturgeon
 */
abstract class Migration {

	/**
	 * Migrate up to this version
	 *
	 * @abstract
	 * @access		public
	 * @return		void
	 */
	abstract public function up();

	// ------------------------------------------------------------------------

	/**
	 * Migrate down to this version
	 *
	 * @abstract
	 * @access		public
	 * @return		void
	 */
	abstract public function down();

	// ------------------------------------------------------------------------

	/**
	 * Magic method that enables access to the CI's loaded classes
	 * using the same syntax as controllers.
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function & __get($key)
	{
		$CI =& get_instance();
		if (is_callable(array($CI, $key)) OR isset($CI->$key) OR property_exists($CI, $key))
		{
			return $CI->$key;
		}
		$CI->lang->load('migrations');
		show_error($CI->lang->line('migrations_class_property_not_found'));
	}

	// ------------------------------------------------------------------------
}
// END Migration Interface

/* End of file Migration_interface.php */
/* Location: ./third_party/migrations/libraries/Migrations_interface.php */