<?php

namespace App\Controller;

use App\Services\MyMailer;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index(MyMailer $mailer)
    {

        $myMailer = $mailer->sendHelloEmail();

        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
}
