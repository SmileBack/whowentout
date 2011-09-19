<?php

class FilesystemServerChannelDriver extends ServerChannelDriver
{

    public function channel_type() {
        return 'PollingChannel';
    }

    public function push($id, $data)
    {
        $config = (object)$this->config;
        $encoded_data = "json_$id(" . json_encode($data) . ")";
        file_put_contents("$config->folder/$id", $encoded_data);
    }

    public function delete($id)
    {
        $config = (object)$this->config;
        unlink("$config->folder/$id");
    }

    public function url($id)
    {
        $config = (object)$this->config;
        return "/$config->folder/$id";
    }
    
}