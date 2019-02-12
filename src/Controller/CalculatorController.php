<?php

namespace App\Controller;

use App\Entity\Calculator;
use App\Form\CalculatorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * @Route("/calculator", name="calculator")
     */
    public function index(Request $request, \App\Services\Calculator $cs)
    {
        $calculator = new Calculator();
        $form = $this->createCalculatorForm($calculator, $request);
        $result = $cs->calculate($calculator);

        if ($form->isSubmitted()) {
            $cs->saveCalculator($calculator, $result);
        }

        $operations = $cs->fetchOperations();

        return $this->render('calculator/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
            'operations' => $operations
        ]);
    }

    /**
     * @Route("calculator/purge")
     */
    public function purge(\App\Services\Calculator $cs)
    {
        $cs->purge();
        $this->getDoctrine();

        return $this->redirectToRoute('calculator');
    }

    private function createCalculatorForm($calculator, $request)
    {

        $form = $this->createForm(CalculatorType::class, $calculator);
        $form->handleRequest($request);

        return $form;
    }
}
