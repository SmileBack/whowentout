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

require_once 'Migration_abstract.php';

// ------------------------------------------------------------------------

/**
 * Migrations Class
 *
 * Utility main controller.
 *
 * @package		Migrations
 * @author		Mat'as Montes
 */
final class Migrations {

	/**
	 * Enable migrations
	 *
	 * @access	private
	 * @var		boolean
	 */
	private $_enabled = FALSE;

	// ------------------------------------------------------------------------

	/**
	 * Path to the migrations directory
	 *
	 * @access	private
	 * @var		string
	 */
	private $_path = '.';

	// ------------------------------------------------------------------------

	/**
	 * Output directly from within the up/down methods
	 *
	 * @access	private
	 * @var		boolean
	 */
	private $_verbose = FALSE;

	// ------------------------------------------------------------------------

	/**
	 * Internal error message
	 *
	 * @access	private
	 * @var		string
	 */
	private $_error = '';

	// ------------------------------------------------------------------------

	/**
	 * Class Version number
	 *
	 * @access	public
	 */
	const VERSION = '1.0';

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
	 * Class constructor, setup config and database migrations table
	 *
	 * @access	public
	 * @return	Migrations
	 */
	public function __construct()
	{

		// Load required files
		$this->config->load('migrations');
		$this->lang->load('migrations');
		$this->load->model('migrations_model');

		// Set class properties to file config values
		$this->_enabled = $this->config->item('migrations_enabled');
		$path = $this->config->item('migrations_path');

		// Ensure migrations are enabled and can be found
		$this->_enabled AND $path OR
			show_error($this->lang->line('migrations_invalid_setup'));

		// Set the migrations path if not done already
		if ($path == '')
		{
			$path = APPPATH.'migrations';
		}

		$this->set_path($path);

		// Install the db table if not installed
		$this->migrations_model->install();
	}

	// ------------------------------------------------------------------------

	/**
	 * Turn on/off migrations
	 *
	 * @access	public
	 * @param	boolean	$flag
	 * @return	void
	 */
	public function set_enabled($flag)
	{
		$this->_enabled = (boolean) $flag;
	}

	// ------------------------------------------------------------------------

	/**
	 * Check if migrations are enabled
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_enabled()
	{
		return $this->_enabled;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set the path to the migrations directory
	 *
	 * @access	public
	 * @return	void
	 */
	public function set_path($path)
	{
		$this->_path = rtrim($path, '/').'/';
	}

	// ------------------------------------------------------------------------

	/**
	 * Get the set migrations path
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_path()
	{
		return $this->_path;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set the property for verbose output
	 *
	 * @access	public
	 * @param	boolean	$state
	 * @return	void
	 */
	public function set_verbose($state)
	{
		$this->_verbose = $state;
	}

	// ------------------------------------------------------------------------

	/**
	 * Check if verbose output is enabled
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_verbose()
	{
		return $this->_verbose;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set an error message
	 *
	 * @access	public
	 * @param	string $message
	 * @return	void
	 */
	public function set_error($message)
	{
		$this->_error = $message;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get the current error message
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_error()
	{
		return $this->_error;
	}

	// ------------------------------------------------------------------------

	/**
	 * Installs the schema up to the last version
	 *
	 * @access	public
	 * @return	void	Outputs a report of the installation
	 */
	public function install()
	{
		// Load all *_*.php files in the migrations path
		$files = glob($this->_path.'*_*'.EXT);
		$file_count = count($files);

		for ($i = 0; $i < $file_count; $i++)
		{
			// Mark wrongly formatted files as FALSE for later filtering
			$name = basename($files[$i],EXT);
			if (!preg_match('/^\d{3}_(\w+)$/',$name))
			{
				$files[$i] = FALSE;
			}
		}

		$migrations = array_filter($files);

		if (!empty($migrations))
		{
			sort($migrations);
			$last_migration = basename(end($migrations));

			// Calculate the last migration step from existing migration
			// filenames and procceed to the standard version migration
			$last_version =	substr($last_migration,0,3);
			return $this->version(intval($last_version,10));
		}


		$this->_error = $this->lang->line('migrations_invalid_path');
		return 0;
	}

	// --------------------------------------------------------------------

	/**
	 * Migrate to a schema version
	 *
	 * Calls each migration step required to get to the schema version of
	 * choice
	 *
	 * @access	public
	 * @param	integer	$version Target schema version
	 * @return	mixed	TRUE if latest, FALSE if failed, integer if upgraded
	 */
	public function version($version)
	{
		// Initialize version and internal position
		$schema_version = $this->migrations_model->get_version();
		$start = $schema_version;
		$stop = $version;
                
		// Migrate up
		if ($version > $schema_version)
		{
			$start++;
			$stop++;
			$step = 1;
		}

		// Migrate down
		else
		{
			$step = -1;
		}

		// Define direction and initialize
		$method = $step == 1 ? 'up' : 'down';
		$migrations = array();

		// Make sure that everything is the way it should be
		for ($i = $start; $i != $stop; $i += $step)
		{
			$f = glob(sprintf($this->_path . '%03d_*'.EXT, $i));

			// Only one migration per step is permitted
			if (count($f) > 1)
			{
				$this->_error = sprintf($this->lang->line('migrations_multiple_version'), $i);
				return 0;
			}

			// Migration step not found
			if (count($f) == 0)
			{
				// If trying to migrate up to a version greater than the last
				// existing one, migrate to the last one.
				if ($step == 1)
				{
					break;
				}

				// If trying to migrate down but we're missing a step,
				// something must definitely be wrong.
				$this->_error = sprintf($this->lang->line('migrations_migration_not_found'), $i);
				return 0;
			}

			$file = basename($f[0]);
			$name = basename($f[0], EXT);

			// Filename validations
			if (preg_match('/^\d{3}_(\w+)$/', $name, $match))
			{
				$match[1] = strtolower($match[1]);

				// Cannot repeat a migration at different steps
				if (in_array($match[1], $migrations))
				{
					$this->_error = sprintf($this->lang->line('migrations_multiple_names'), $match[1]);
					return 0;
				}

				// Attempt to load the migration
				include $f[0];
				$class = 'Migration_'.ucfirst($match[1]);

				// Class is missing
				if ( ! class_exists($class))
				{
					$this->_error = sprintf($this->lang->line('migrations_class_doesnt_exist'), $class);
					return 0;
				}

				// Doesn't contain required methods
				if ( ! is_callable(array($class, 'up')) || ! is_callable(array($class, 'down'))) {
					$this->_error = sprintf($this->lang->line('migrations_missing_method'), $class);
					return 0;
				}

				$migrations[] = $match[1];
			}

			// File name is invalid
			else
			{
				$this->_error = sprintf($this->lang->line('invalid_migration_filename'), $file);
				return 0;
			}
		}

		$version = $i + ($step == 1 ? -1 : 0);

		// Quit here if migrations already at the latest version
		if ($migrations === array())
		{
			if ($this->is_verbose())
			{
				echo "Nothing to do, bye!\n";
			}

			return TRUE;
		}

		// Output the header
		if ($this->is_verbose())
		{
			echo '<p>Current schema version: '.$schema_version.'<br/>';
			echo 'Moving '.$method.' to version '.$version.'</p>';
			echo '<hr/>';
		}

		// Loop through the migrations
		foreach($migrations AS $m)
		{
			// Output container open
			if ($this->is_verbose())
			{
				echo "$m:<br />";
				echo '<blockquote>';
			}

			// Run the migration
			$class = 'Migration_'.ucfirst($m);
			call_user_func(array(new $class, $method));

			// Output container close
			if ($this->is_verbose())
			{
				echo '</blockquote>';
				echo '<hr/>';
			}

			// Update the version
			$schema_version += $step;
			$this->migrations_model->set_version($schema_version);
		}

		// Output the footer
		if ($this->is_verbose())
		{
			echo "<p>All done. Schema is at version $schema_version.</p>";
		}

		return $schema_version;
	}

	// --------------------------------------------------------------------

	/**
	 * Set's the schema to the latest migration
	 *
	 * @access	public
	 * @return	mixed	TRUE if already latest, FALSE if failed, int if upgraded
	 */
	public function latest()
	{
		$version = $this->config->item('migrations_version');
		return $this->version($version);
	}

	// ------------------------------------------------------------------------
}
// END Migrations Class

/* End of file Migrations.php */
/* Location: ./third_party/migrations/libraries/Migrations.php */