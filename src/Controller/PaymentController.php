<?php

namespace App\Controller;

use App\Repository\CartListRepository;
use App\Repository\UserCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\StripeService;

class PaymentController extends AbstractController
{
    private $security;
    private $cartListRepository;
    private $userCartRepository;
    private $stripeService;

    public function __construct(Security $security, CartListRepository $cartListRepository, UserCartRepository $userCartRepository, StripeService $stripeService)
    {
        $this->security = $security;
        $this->cartListRepository = $cartListRepository;
        $this->userCartRepository = $userCartRepository;
        $this->stripeService = $stripeService;
    }

    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(): Response
    {
        $user = $this->security->getUser();

        // Récupérer le panier de l'utilisateur
        $userCart = $this->userCartRepository->findOneBy(['user' => $user]);

        if (!$userCart) {
            throw new \Exception("Panier non trouvé.");
        }

        // Récupérer les éléments du panier
        $cartItems = $this->cartListRepository->findBy(['userCart' => $userCart]);

        // Créer la session de paiement avec Stripe
        $session = $this->stripeService->createCheckoutSession($cartItems);

        // Rediriger vers la page de paiement Stripe
        return $this->redirect($session->url);
    }
}