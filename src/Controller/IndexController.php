<?php

namespace App\Controller;

use App\Entity\Card;
use App\Security\User;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/test")
     */
    public function test(Request $request, SubscriptionService $subscriptionService)
    {


        $card = new Card();

        $form = $this->createFormBuilder($card)
            ->add('number', NumberType::class)
            ->add('cvvNumber', NumberType::class)
            ->add('type', ChoiceType::class, ['choices' => Card::getTypes()])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriptionService->activateAndSendMail($this->getUser());
        }

        return $this->render("card.html.twig", ['form' => $form->createView()]);
    }

    protected function getUser(): User
    {
        // fixme: mock
        return new User();
    }
}
