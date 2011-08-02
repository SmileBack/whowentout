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
 * Migrations model, converted from main library
 *
 * @package		Migrations
 * @author		Mat'as Montes
 */
class Migrations_model {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	Migrations_model
	 */
	public function __construct()
	{
		$this->lang->load('migrations');
	}

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
		show_error($CI->lang->line('migrations_class_property_not_found'));
	}

	// ------------------------------------------------------------------------

	/**
	 * Creates the user defined migrations table
	 *
	 * @access	public
	 * @return	void
	 */
	public function install()
	{
		// Load dbforge library
		$this->load->dbforge();

		// Get user defined table name
		$table = $this->config->item('migrations_table');

		// Add the schema table if not already there
		if ( ! $this->db->table_exists($table))
		{
			// Add table field
			$this->dbforge->add_field(array(
				'version' => array('type' => 'INT', 'constraint' => 3),
			));

			// Make the table
			$this->dbforge->create_table($table, TRUE);

			// Initialize it to version 0
			$this->db->insert($table, array('version' => 0));
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Drops the user defined migrations table
	 *
	 * @access	public
	 * @return	void
	 */
	public function uninstall()
	{
		// Load dbforge library
		$this->load->dbforge();

		// Get user defined table name
		$table = $this->config->item('migrations_table');

		// Add the schema table if not already there
		if ($this->db->table_exists($table))
		{
			$this->dbforge->drop_table($table);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieves current schema version
	 *
	 * @access	public
	 * @return	integer	Current Schema version
	 */
	public function get_version()
	{
		// Get user defined table name
		$table = $this->config->item('migrations_table');

		// Get the version number
		$row = $this->db->get($table)->row();
		return $row ? $row->version : 0;
	}

	// ------------------------------------------------------------------------

	/**
	 * Stores the current schema version
	 *
	 * @access	public
	 * @param	integer	$version Schema version reached
	 * @return	void
	 */
	public function set_version($version)
	{
		// Get user defined table name
		$table = $this->config->item('migrations_table');

		// Save the version number
		return $this->db->update($table, array(
			'version' => $version
		));
	}

	// ------------------------------------------------------------------------

}
// END Migrations_model Class

/* End of file Migrations_model.php */
/* Location: ./third_party/migrations/models/Migrations_model.php */