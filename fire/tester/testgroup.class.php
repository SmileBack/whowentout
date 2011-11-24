<?php

class TestGroup
{

    private $report = array();
    private $current_test_method = null;
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
            'passed' => true,
            'assertion_pass_count' => 0,
            'assertion_count' => 0,
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
        if ($expression !== true && $this->current_test['passed'] == true) {
            $this->current_test['passed'] = false;
            $this->current_test['message'] = $message;
            $this->current_test['line'] = $this->calling_line();
            $this->current_test['file'] = $this->calling_file();
        }
        else {
            $this->current_test['assertion_pass_count']++;
        }

        $this->current_test['assertion_count']++;
    }

    protected function assert_equal($actual, $expected, $message = '')
    {
        if ($actual != $expected && $this->current_test['passed'] == true) {
            $this->current_test['passed'] = false;
            $this->current_test['message'] = $message;
            $this->current_test['actual'] = $actual;
            $this->current_test['expected'] = $expected;
            $this->current_test['line'] = $this->calling_line();
            $this->current_test['file'] = $this->calling_file();
        }
        else {
            $this->current_test['assertion_pass_count']++;
        }

        $this->current_test['assertion_count']++;
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
