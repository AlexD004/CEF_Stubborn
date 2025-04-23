<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\UserCart;
use App\Entity\CartList;
use Doctrine\ORM\EntityManagerInterface;

class CartTest extends TestCase
{
    public function testAddProductToCart()
    {
        // Create a User
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('test');

        // Create a Product
        $product = new Product();
        $product->setName('Test Sweat');
        $product->setPrice(29.99);

        // Create UserCart
        $cart = new UserCart();
        $cart->setUser($user);

        // Create CartList (item in cart)
        $cartItem = new CartList();
        $cartItem->setUserCart($cart);
        $cartItem->setProduct($product);
        $cartItem->setQuantity(2);

        // Assert product added
        $this->assertEquals('Test Sweat', $cartItem->getProduct()->getName());
        $this->assertEquals(2, $cartItem->getQuantity());
    }
}