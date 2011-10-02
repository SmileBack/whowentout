<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Tester
{

    private $ci;
    private $report = array();
    private $test_group_name = NULL;

    function __construct($config = NULL)
    {
        $ci =& get_instance();
    }

    function report()
    {
        return $this->report;
    }

    function run()
    {
        $tests = $this->get_test_group($this->test_group_name);
        $tests->run();
        $this->report[$this->test_group_name] = $tests->report();
        return $this->report();
    }

    function groups()
    {
        $names = array();
        $files = $this->files(APPPATH . 'tests');
        foreach ($files as $file) {
            $names[] = preg_replace('/\_tests.php$/', '', basename($file));
        }
        return $names;
    }

    function load($group)
    {
        if (!$this->test_group_exists($group))
            return FALSE;

        $this->test_group_name = $group;

        return TRUE;
    }

    /**
     * @return TestGroup
     */
    private function get_test_group($group)
    {
        require_once $this->test_group_path($group);
        $class = "{$group}_tests";
        return new $class;
    }

    private function test_group_path($group)
    {
        return APPPATH . "tests/{$group}_tests.php";
    }

    private function test_group_exists($tests_name)
    {
        return file_exists($this->test_group_path($tests_name));
    }

    function files($path, $include_subdirectories = FALSE)
    {
        if (!is_dir($path))
            return FALSE;

        $files = array();

        $iterator = $include_subdirectories
                ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path))
                : new DirectoryIterator($path);

        foreach ($iterator as $file) {
            // isDot method is only available in DirectoryIterator items
            // isDot check skips '.' and '..'
            if ($include_subdirectories == FALSE && $file->isDot())
                continue;
            // Standardize to forward slashes
            $files[] = str_replace('\\', '/', $file->getPathName());
        }

        return $files;
    }

}

class TestGroup
{

    private $report = array();
    private $current_test_method = NULL;
    private $current_test = array();

    function report()
    {
        return $this->report;
    }

    function run()
    {
        $this->setup();

        $this->report = array(
            'tests' => array(),
            'total' => 0,
            'passed' => 0,
        );
        $methods = $this->get_tests();
        foreach ($methods as $method) {
            $this->run_test($method);
        }

        $this->teardown();

        return $this->report;
    }

    function get_tests()
    {
        $methods = get_class_methods($this);
        $methods = preg_grep('/^test_/', $methods);
        return $methods;
    }

    protected function setup()
    {
    }

    protected function teardown()
    {
    }

    private function run_test($method)
    {
        $this->current_test_method = $method;
        $this->current_test = array(
            'name' => $method,
            'passed' => TRUE,
            'message' => '',
        );

        $this->$method();

        if ($this->current_test['passed']) {
            $this->report['passed']++;
        }
        $this->report['total']++;

        $this->report['tests'][$method] = $this->current_test;
    }

    protected function assert_true($expression, $message = '')
    {
        if ($expression !== TRUE && $this->current_test['passed'] == TRUE) {
            $this->current_test['passed'] = FALSE;
            $this->current_test['message'] = $message;
            $this->current_test['line'] = $this->calling_line();
            $this->current_test['file'] = $this->calling_file();
        }
    }

    protected function assert_equal($actual, $expected, $message = '')
    {
        if ($actual != $expected && $this->current_test['passed'] == TRUE) {
            $this->current_test['passed'] = FALSE;
            $this->current_test['message'] = $message;
            $this->current_test['actual'] = @strval($actual);
            $this->current_test['expected'] = @strval($expected);
            $this->current_test['line'] = $this->calling_line();
            $this->current_test['file'] = $this->calling_file();
        }
    }

    protected function calling_line()
    {
        $backtrace = debug_backtrace();
        return $backtrace[1]['line'];
    }

    protected function calling_file()
    {
        $backtrace = debug_backtrace();
        return $backtrace[1]['file'];
    }

    /* utility methods */

    protected function clear_database()
    {
        $ci =& get_instance();
        $tables = $this->database_tables();
        $ci->db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            $ci->db->truncate($table);
        }
        $ci->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    protected function database_tables()
    {
        $ci =& get_instance();
        $tables = array();
        $rows = $ci->db->query('SHOW TABLES')->result();
        foreach ($rows as $row) {
            $row = (array)$row;
            $tables[] = array_pop($row);
        }
        return $tables;
    }

}
