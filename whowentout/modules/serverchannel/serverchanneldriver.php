<?php

abstract class ServerChannelDriver extends Driver
{
    abstract function channel_type();
    abstract function trigger($channel, $event_name, $data);
    abstract function delete($channel);
    abstract function url($channel);
}
