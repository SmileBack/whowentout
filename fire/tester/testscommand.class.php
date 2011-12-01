<?php

class TestsCommand extends Command
{

    function run($args)
    {
        if (empty($args))
            $this->show_help();
        elseif ($args[0] == 'list')
            $this->list_tests();
        elseif ($args[0] == 'run')
            $this->run_test($args[1]);
    }

    function list_tests()
    {
        $tester = new Tester();
        $groups = $tester->get_test_groups();

        $lines = array();
        foreach ($groups as $group) {
            $lines[] = ' - ' . $group;
        }

        print implode("\n", $lines);
    }

    function run_test($group)
    {
        $tester = new Tester();
        $exists = $tester->load($group);

        if (!$exists) {
            print "The test group $group doesn't exist.";
            exit;
        }

        $tester->run();
        $report = $tester->report();

        $lines = array();
        foreach ($report as $group_name => $group) {
            $lines[] = '--' . strtoupper($group_name) . '--';
            $lines[] = $group['passed'] . '/' . $group['total'] . ' passed';
            foreach ($group['tests'] as $t) {
                $lines[] = ($t['passed'] ? '[Y]' : '[N]') . ' ' . $t['name'];
            }
        }

        print implode("\n", $lines);
        print "\n";
    }

    function show_help()
    {
        $help = array(
            'usage:',
            'tests list',
            'tests run testname',
        );
        print implode("\n", $help);
    }

}
