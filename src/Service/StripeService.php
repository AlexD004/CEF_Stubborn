<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class StripeService
{
    private string $stripeSecretKey;
    private UrlGeneratorInterface $router;

    public function __construct(string $stripeSecretKey, UrlGeneratorInterface $router)
    {
        $this->stripeSecretKey = $stripeSecretKey;
        $this->router = $router;
    }

    public function createCheckoutSession(array $cartItems): StripeSession
    {
        Stripe::setApiKey($this->stripeSecretKey); 

        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                    ],
                    'unit_amount' => $item->getProduct()->getPrice() * 100,
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->router->generate('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->router->generate('cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}