<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Twig\Environment;


class MyMailer
{
    public function __construct(
        \Swift_Mailer $mailer,
        LoggerInterface $logger,
       Environment $twig
    )
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->twig=$twig;
    }

    public function sendHelloEmail()
    {
        $message = new \Swift_Message('Hello from huge coders');
        $message->setFrom('hugecoders.team@gmail.com');
        $message->setTo('sedkighanmy@gmail.com');
        $message->addPart(
            $this->twig->render(
                'email/hello.html.twig',
                ['welcome'=>'welcome to huge coders']
                )
        );

        $this->mailer->send($message);

        $this->logger->info("Mail sent !");
    }
}