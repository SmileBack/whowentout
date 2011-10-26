<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller
{

    function index()
    {
        if (ENVIRONMENT != 'test')
            show_error('ENVIRONMENT must be test.');

        $tester = new Tester();
        $groups = $tester->get_test_groups();

        print r('tester/tests', array('groups' => $groups));
    }

    function group($group = NULL)
    {
        if (ENVIRONMENT != 'test')
            show_error('ENVIRONMENT must be test.');

        $tester = new Tester();
        $exists = $tester->load($group);

        if (!$exists)
            show_404();

        $tester->run();

        $report = $tester->report();

        print r('tester/report', array('report' => $report));
    }

}
