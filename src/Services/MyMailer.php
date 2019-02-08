<?php
namespace App\Services;

use Psr\Log\LoggerInterface;

class MyMailer {
    public function __construct(
        \Swift_Mailer $mailer,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function sendHelloEmail(){
        $message = new \Swift_Message('Hello from huge coders');
        $message->setFrom('hugecoders.team@gmail.com');
        $message->setTo('sedkighanmy@gmail.com');
        $message->addPart('Welcom !');

        $this->mailer->send($message);

        $this->logger->info("Mail sent !");
    }
}