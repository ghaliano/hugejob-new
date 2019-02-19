<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Offer;
use App\Form\ApplicationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    /**
     * @Route("/application/{id}", name="apply")
     */
    public function apply(Request $request,Offer $offer)
    {
        $application=new Application();
        $application->setUser($this->getUser());
        $application->setOffer($offer);
            $form=$this->createForm(ApplicationType::class,$application);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            return $this->redirectToRoute('offer_index');
        }
        return $this->render('application/apply.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
