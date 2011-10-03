<?php

class XCollege_Tests extends TestGroup
{

    function setup()
    {
        parent::setup();
        $this->clear_database();
    }

    function teardown()
    {
        parent::teardown();
    }

    function test_addition()
    {
        $this->assert_equal(1+1, 2, 'addition of 1+1');
    }

}
