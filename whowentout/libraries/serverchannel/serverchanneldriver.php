<?php

abstract class ServerChannelDriver extends Driver
{
    abstract function channel_type();
    abstract function push($channel, $data);
    abstract function delete($channel);
    abstract function url($channel);
}
