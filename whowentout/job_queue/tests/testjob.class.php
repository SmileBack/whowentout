<?php

class TestJob extends Job
{

    public $required_options = array('path', 'data');

    function run()
    {
        file_put_contents($this->options['path'], $this->options['data']);
    }

}
