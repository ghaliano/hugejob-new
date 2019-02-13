<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Form\OfferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/", name="offer_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $offers = $this
            ->getDoctrine()
            ->getRepository(Offer::class)
            ->search($request->get('page', 1))
        ;

        return $this->render('offer/index.html.twig', [
            'companies' =>$this
                ->getDoctrine()
                ->getRepository(Company::class)
                ->findAll(),
            'pages' => count($offers),
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/offer/new", name="offer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/offer/{id}", name="offer_show", methods={"GET"})
     */
    public function show(int $id, Request $request): Response
    {
        $offer = $this
            ->getDoctrine()
            ->getRepository(Offer::class)
            ->find($id)
        ;

        if (!$offer) {
            $this->addFlash('warning', "Attention !");
            $this->addFlash('warning', "Offre n'existe pas");
            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/offer/{id}/edit", name="offer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_index', [
                'id' => $offer->getId(),
            ]);
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/offer/{id}", name="offer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index');
    }
}
