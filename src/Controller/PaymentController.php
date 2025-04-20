<?php

namespace App\Controller;

use App\Repository\CartListRepository;
use App\Repository\UserCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;

class PaymentController extends AbstractController
{
    private $security;
    private $cartListRepository;
    private $userCartRepository;
    private $stripeService;
    private $entityManager;

    public function __construct(Security $security, CartListRepository $cartListRepository, UserCartRepository $userCartRepository, StripeService $stripeService, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->cartListRepository = $cartListRepository;
        $this->userCartRepository = $userCartRepository;
        $this->stripeService = $stripeService;
        $this->entityManager = $entityManager;
    }

    #[Route('/create-payment-intent', name: 'create_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(): JsonResponse
    {
        $user = $this->security->getUser();
        $userCart = $this->userCartRepository->findOneBy(['user' => $user]);
        $cartItems = $this->cartListRepository->findBy(['userCart' => $userCart]);

        $amount = 0;
        foreach ($cartItems as $item) {
            $amount += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        $amountInCents = $amount * 100;

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'eur',
        ]);

        return $this->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(): Response
    {

        $user = $this->security->getUser();
        $userCart = $this->userCartRepository->findOneBy(['user' => $user]);
        $cartItems = $this->cartListRepository->findBy(['userCart' => $userCart]);

        return $this->render('/payment/payment.html.twig', [
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/checkout/success', name: 'checkout_success')]
    public function success(): Response
    {
        $user = $this->security->getUser();
        $userCart = $this->userCartRepository->findOneBy(['user' => $user]);

        $cartItems = $this->cartListRepository->findBy(['userCart' => $userCart]);

        foreach ($cartItems as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->remove($userCart);

        $this->entityManager->flush();

        $this->addFlash('success', 'Paiement réussi !');
        return $this->render('payment/success.html.twig');
    }
    
    #[Route('/checkout/cancel', name: 'checkout_cancel')]
    public function cancel(): Response
    {
        $this->addFlash('error', 'Paiement annulé.');
        return $this->redirectToRoute('cart_index');
    }
}
