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
 * Migration Example Controller
 *
 * @package	Migrations
 */
class Migrate extends CI_Controller
{
	/**
	 * Controller constructor
	 *
	 * Loads the necessary libraries and checks that the library is enabled
	 *
	 * @access	public
	 * @return	Migrate
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->add_package_path(APPPATH.'third_party/migrations/');

		//load the lib, all requried models/config/language loaded internally
		$this->load->library('migrations');

		// Options to configure the library at runtime
		$this->migrations->set_verbose(TRUE);//boolean
//		$this->migrations->set_path(APPPATH.'migrations');//string trailing slash added for you
//		$this->migrations->set_enabled(TRUE);//boolean
		//$this->migrations->set_error('Some error message.'); //string

		// The migrations table is installed during construction but
		// you can install or uninstall it manually like this:
		//$this->migrations_model->install();
		//$this->migrations_model->uninstall();

		/** VERY IMPORTANT - only turn this on in the config when you need it. */
		if ( ! $this->migrations->is_enabled())
		{
			show_error($this->lang->line('migrations_not_enabled'));
		}
	}

	// ------------------------------------------------------------------------

	//
	/**
	 * Install up to the most up-to-date version.
	 *
	 * @access	public
	 * @return	void
	 */
	public function install()
	{
		if ( ! $this->migrations->install())
		{
			$error = $this->migrations->get_error();
			show_error($error);
		}

		echo "<br />Migration Successful<br />";
	}

	//
	/**
	 * This will migrate up to the configured version
	 *
	 * @access	public
	 * @param	integer $id
	 * @return	void
	 */
	public function version($id = NULL)
	{
		// No $id supplied? Use the config version
		$id OR $id = $this->config->item('migrations_version');

		if ( ! $this->migrations->version($id))
		{
			$error = $this->migrations->get_error();
			show_error($error);
		}

		echo '<br />Migration Successful<br />';
	}

	// ------------------------------------------------------------------------
}
// END Migrations Example Controller

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */