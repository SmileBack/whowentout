<?php

require_once dirname(__FILE__) . '/swift/swift_required.php';

class SwiftEmailDriver extends EmailDriver
{

    function send_email($to, $subject, $body)
    {
        $config = (object)$this->config;

        $transport = Swift_SmtpTransport::newInstance($config->server, $config->port, $config->encryption)
                ->setUsername($config->username)
                ->setPassword($config->password);

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance($subject)
                ->setBody($body, 'text/html')
                ->setFrom($config->username, 'WhoWentOut')
                ->setTo(array($to->email => $to->full_name));

        $result = $mailer->send($message);
    }

}
