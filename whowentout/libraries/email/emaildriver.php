<?php

abstract class EmailDriver extends Driver
{
  abstract function send_email($to, $subject, $body);
}
