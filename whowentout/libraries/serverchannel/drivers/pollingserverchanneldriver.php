<?php

class PollingServerChannelDriver extends ServerChannelDriver
{

    private $storage_preset;
    private $ci;

    function __construct($config)
    {
        parent::__construct($config);

        $this->ci =& get_instance();
        $this->ci->load->library('storage');

        $this->storage_preset = $this->config['preset'] . '_channels';
        $this->ci->storage->add_preset($this->storage_preset, $this->config['storage']);
    }

    function channel_type()
    {
        return 'PollingChannel';
    }

    function push($channel, $data)
    {
        $encoded_data = "json_$channel(" . json_encode($data) . ')';
        $this->ci->storage->saveText($this->storage_preset, $channel, $encoded_data);
    }

    function delete($channel)
    {
        $this->ci->storage->delete($this->storage_preset, $channel);
    }

    function url($channel)
    {
        return $this->ci->storage->url($this->storage_preset, $channel);
    }
    
}
