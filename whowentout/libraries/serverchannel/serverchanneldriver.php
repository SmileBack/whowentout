<?php

abstract class ServerChannelDriver extends Driver
{
    abstract function push($channel, $data);
    abstract function delete($channel);
    abstract function url($channel);
}
