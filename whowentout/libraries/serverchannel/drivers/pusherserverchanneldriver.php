<?php

require_once APPPATH . 'third_party/pusher.php';

class PusherServerChannelDriver extends ServerChannelDriver
{

    private $pusher;

    function channel_type()
    {
        return 'PusherChannel';
    }

    public function push($channel, $data)
    {
        $this->pusher()->trigger($channel, 'datareceived', $data);
    }

    public function delete($id)
    {
    }

    public function url($id)
    {
        return '';
    }

    /**
     * @return Pusher
     */
    function pusher()
    {
        if ($this->pusher == NULL) {
            $this->pusher = new Pusher($this->config['app_key'], $this->config['app_secret'], $this->config['app_id']);
        }
        return $this->pusher;
    }

}
