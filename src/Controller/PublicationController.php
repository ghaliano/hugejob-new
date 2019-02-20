<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Services\PublicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\SerializerInterface;

class PublicationController extends AbstractController
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/publication", name="publication", methods={"GET"})
     */
    public function index(PublicationService $publicationService)
    {
        $publications = $publicationService->search();

        return new Response(
            $this->serializer->serialize(
                $publications,
                'json',
                [
                    'groups' => 'publication_list'
                ]
            )
        );
    }

    /**
     * @Route("/publication/{id}", name="publication_show", methods={"GET"})
     */
    public function show(Publication $publication)
    {
        return new Response(
            $this->serializer->serialize(
                $publication,
                'json',
                [
                    'groups' => 'publication_list'
                ]
            )
        );
    }

    /**
     * @Route("publication", methods={"POST"})
     */
    public function add(Request $request)
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $em->persist($publication);
        $em->flush();

        return $this->json($this->serializer->serialize(
            $publication,
            'json',
            [
                'groups' => 'publication_list'
            ]
        ));
    }

}
