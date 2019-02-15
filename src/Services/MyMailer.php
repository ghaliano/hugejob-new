<?php

namespace App\Services;

use App\Entity\User;
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
    public function confirmSignup(User $user){
        $message = new \Swift_Message("il vous reste qu'une seule étape !");
        $message->setFrom('hugecoders.team@gmail.com');
        $message->setTo($user->getEmail());
        $message->addPart(
            $this->twig->render(
                'email/confirm.html.twig',
                ['user'=>$user]
            )
        );

        $this->mailer->send($message);

        $this->logger->info("Confirm Mail sent !");
    }

    public function sendForgotPwd(User $user){
        $message = new \Swift_Message("un nouveau message a ete genere !");
        $message->setFrom('hugecoders.team@gmail.com');
        $message->setTo($user->getEmail());
        $message->addPart(
            $this->twig->render(
                'email/forgot.html.twig',
                ['user'=>$user]
            )
        );

        $this->mailer->send($message);

        $this->logger->info("Forgot Mail sent !");
    }
}