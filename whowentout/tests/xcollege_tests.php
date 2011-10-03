<?php

class XCollege_Tests extends TestGroup
{

    function setup()
    {
        $this->clear_database();
    }

    function teardown()
    {
        
    }

    function test_addition()
    {
        $this->assert_equal(1+1, 2, 'addition of 1+1');
    }

}
