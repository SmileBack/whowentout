<?php

abstract class EmailerDriver extends Driver
{
  abstract function send_email($to, $subject, $body);
}
