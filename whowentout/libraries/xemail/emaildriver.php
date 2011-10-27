<?php

abstract class XEmailDriver extends Driver
{
  abstract function send_email($to, $subject, $body);
}
