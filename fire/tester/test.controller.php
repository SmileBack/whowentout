<?php

class Test_Controller extends Controller
{

    function index()
    {
        $tester = new Tester();
        $groups = $tester->get_test_groups();

        print r('tests', array('groups' => $groups));
    }

    function group($group = null)
    {
        $tester = new Tester();
        $exists = $tester->load($group);

        if (!$exists)
            show_404();

        $tester->run();

        $report = $tester->report();

        print r('test_results', array('report' => $report));
    }

}
