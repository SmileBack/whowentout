<?php

require_once APPPATH . 'third_party/swift/swift_required.php';

class SwiftXEmailDriver extends XEmailDriver
{

    function send_email($to, $subject, $body)
    {
        $config = (object)$this->config;

        if ( ! isset($to->full_name) )
            $to->full_name = $to->email;

        $transport = Swift_SmtpTransport::newInstance($config->server, $config->port, $config->encryption)
                ->setUsername($config->username)
                ->setPassword($config->password);

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance($subject)
                ->setBody($body, 'text/html')
                ->setFrom($config->username, $config->from)
                ->setTo(array($to->email => $to->full_name));

        $result = $mailer->send($message);
    }

}
