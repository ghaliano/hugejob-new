<?php

namespace App\Controller;

use App\Entity\ChangePwd;
use App\Entity\Company;
use App\Entity\ForgotPwd;
use App\Entity\User;
use App\Form\ChangePwdType;
use App\Form\ForgotFormType;
use App\Form\SignUpRecruiterType;
use App\Form\SignUpType;
use App\Services\MyMailer;
use App\Services\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/se-deconnecter", name="app_logout")
     */
    public function logout()
    {

    }

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
        MyMailer $mailer,
        UserService $userService
    )
    {
        // whatever *your* User object is
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->processSignup($user, 'ROLE_CANDIDATE');
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
     * @Route("/signup-recruiter", name="app_signup_recruiter")
     */
    public function registerRecruiter(
        Request $request,
        UserService $userService
    )
    {
        // whatever *your* User object is
        $user = new User();
        $user->addCompany(new Company());
        $form = $this->createForm(SignUpRecruiterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->processSignup($user, 'ROLE_RECRUITER');
            $this->addFlash(
                'success',
                'votre inscription a été effectué avec succés, un email vous a été enevoyé.'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signup_recruiter.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("confirm/{token}", name="app_confirm")
     */
    public function confirm($token, UserService $userService)
    {
        $user = $this->fetchUserByToken($token);

        if ($user) {
            $userService->confirmUser($user);
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
    public function forgotPwd(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        MyMailer $mailer,
        UserService $userService)
    {
        $forgotPwd = new ForgotPwd();
        $form = $this->createForm(ForgotFormType::class, $forgotPwd);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $this->getDoctrine()->getRepository(User::class)
                ->findOneBy(['email' => $forgotPwd->getEmail()]);

            if ($user) {
                $userService->processForgotPwd($user);
                $this->addFlash(
                    'info',
                    'voici un code permanant, un email vous a été enevoyé.'
                );

                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('security/forgot.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("changePwd", name="change_pwd")
     */
    public function changePwd(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        MyMailer $mailer,
        UserService $userService
    )
    {
        $changePwd = new changePwd();
        $form = $this->createForm(ChangePwdType::class,
            $changePwd);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user = $this->getUser();

            if ($passwordEncoder
                ->isPasswordValid(
                    $user,
                    $changePwd->getOldPwd()
                )
            ) {
                $user->setPassword($passwordEncoder
                    ->encodePassword($user,
                        $changePwd->getNewPwd()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('offer_index');

            }
        }
        return $this->render(
            'security/change_pwd.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    private function fetchUserByToken($token)
    {
        return $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);
    }
}
