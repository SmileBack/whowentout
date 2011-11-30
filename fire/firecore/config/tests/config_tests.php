<?php

class Config_Tests extends TestGroup
{

    function test_load_config()
    {
        $config = new ConfigSource( f()->index() );
        $sample_config = $config->load('sample_config');

        $this->assert_equal($sample_config['name'], 'foo');
        $this->assert_equal($sample_config['number'], 45);
    }

    function test_load_nonexistent_config()
    {
        $config = new ConfigSource( f()->index() );
        $non_existent_config = $config->load('non_existent');
        $this->assert_equal($non_existent_config, null);
    }

}
