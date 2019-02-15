<?php

namespace App\Controller;


use App\Entity\ForgotPwd;
use App\Entity\User;
use App\Form\ForgotFormType;
use App\Form\SignUpType;
use App\Services\MyMailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/signup", name="app_signup")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        MyMailer $mailer
    )
    {
        // whatever *your* User object is
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $encoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );

            $user->setPassword($encoded);
            $user->setToken(uniqid());
            $user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $mailer->confirmSignup($user);
            $this->addFlash(
                'success',
                'votre inscription a été effectué avec succés, un email vous a été enevoyé.'
            );

            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("confirm/{token}", name="app_confirm")
     */
    public function confirm($token)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);

        if ($user) {
            $user->setEnable(true);
            $user->setToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                'Votre compte est validé, veuillez vous coneceter.'
            );

            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route ("forgotPwd", name="forgot_pwd")
     */

    public function forgotPwd(Request $request, UserPasswordEncoderInterface $encoder,
                              MyMailer $mailer)
    {
        $forgotPwd = new ForgotPwd();
        $form = $this->createForm(ForgotFormType::class, $forgotPwd);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $this->getDoctrine()->getRepository(User::class)
                ->findOneBy(['email' => $forgotPwd->getEmail()]);

            if ($user) {
                $randomPwd = uniqid();
                $user->setPlainPassword($randomPwd);
                $encoded = $encoder->encodePassword(
                    $user,
                    $randomPwd
                );

                $user->setPassword($encoded);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $mailer->sendForgotPwd($user);
                $this->addFlash(
                    'info',
                    'voici un code permanant, un email vous a été enevoyé.'
                );

                return $this->redirectToRoute('app_login');


            }

        }
        return $this->render('security/forgot.html.twig',['form'=>$form->createView()]);

    }
}
