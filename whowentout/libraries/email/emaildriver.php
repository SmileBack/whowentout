<?php

abstract class EmailDriver
{
  
  protected $config;
  
  function __construct($config) {
    $this->config = $config;
  }
  
  abstract function send_email($to, $subject, $body);
  
}
