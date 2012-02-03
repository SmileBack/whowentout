<?php

require_once dirname(__FILE__) . '/../swiftmailer/swift_required.php';

class SwiftEmailerDriver extends EmailerDriver
{

    function send_email($recipient_name, $recipient_email, $subject, $body)
    {
        $config = (object)$this->options;

        $transport = Swift_SmtpTransport::newInstance($config->server, $config->port, $config->encryption)
                ->setUsername($config->username)
                ->setPassword($config->password);

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance($subject)
                                ->setBody($body, 'text/html')
                                ->setFrom($config->username, $config->from)
                                ->setTo(array($recipient_email => $recipient_name));

        $result = $mailer->send($message);
    }

}
