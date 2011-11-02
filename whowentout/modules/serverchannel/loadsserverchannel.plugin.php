<?php
class LoadServerChannelPlugin extends Plugin
{

    function on_fire_boot($e)
    {
        $config =& load_class('Config', 'core');
        $config->load('serverchannel');
        $item = $config->item('serverchannel');

        $e->f->create('serverchannel', 'serverchannel', $item['default']);
    }
    
}
