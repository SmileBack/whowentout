<?php

class Tester
{

    private $report = array();
    private $test_group_name = null;

    function __construct($config = null)
    {
    }

    function report()
    {
        return $this->report;
    }

    function load($test_group)
    {
        $this->test_group_name = $test_group;
        return true;
    }

    function run()
    {
        $tests = $this->get_test_group($this->test_group_name);
        $tests->run();
        $this->report[$this->test_group_name] = $tests->report();
        return $this->report();
    }

    function get_test_groups()
    {
        return f()->class_loader()->get_subclass_names('TestGroup');
    }

    /**
     * @return TestGroup
     */
    private function get_test_group($group)
    {
        return f()->class_loader()->init($group);
    }

}
