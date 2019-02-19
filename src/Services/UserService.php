<?php
namespace App\Services;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $em,
        MyMailer $mailer
    )
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    public function confirmUser(User $user)
    {
        $user->setEnable(true);
        $user->setToken(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function processSignup(User $user, $role)
    {
        $encoded = $this->encoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );

        $user->addRole($role);

        $user->setPassword($encoded);
        $user->setToken(uniqid());

        $this->em->persist($user);
        $this->em->flush();
        $this->mailer->confirmSignup($user);
    }

    public function processForgotPwd(User $user)
    {
        $randomPwd = uniqid();
        $user->setPlainPassword($randomPwd);
        $encoded = $this->encoder->encodePassword(
            $user,
            $randomPwd
        );

        $user->setPassword($encoded);
        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->sendForgotPwd($user);
    }
}