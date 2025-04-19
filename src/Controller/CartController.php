<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\UserCart;
use App\Entity\CartList;
use App\Form\CartAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart_index')]
    public function index(EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();
        $userCart = $em->getRepository(UserCart::class)->findOneBy(['user' => $user]);

        if (!$userCart) {
            return $this->render('cart.html.twig', ['cartItems' => [], 'total' => 0]);
        }

        $items = $em->getRepository(CartList::class)->findBy(['userCart' => $userCart]);

        // Calcul du total
        $total = 0;
        foreach ($items as $item) {
            $total += $item->getQuantity() * $item->getProduct()->getPrice();
        }

        return $this->render('cart.html.twig', [
            'cartItems' => $items,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();
        
        $userCart = $em->getRepository(UserCart::class)->findOneBy(['user' => $user]);
        if (!$userCart) {
            $userCart = new UserCart();
            $userCart->setUser($user);
            $em->persist($userCart);
        }

        $form = $this->createForm(CartAddType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $size = $form->get('size')->getData();
            $quantity = $form->get('quantity')->getData();

            $existing = $em->getRepository(CartList::class)->findOneBy([
                'userCart' => $userCart,
                'product' => $product,
                'size' => $size
            ]);

            if ($existing) {
                $existing->setQuantity($existing->getQuantity() + $quantity);
            } else {
                $cartItem = new CartList();
                $cartItem->setProduct($product);
                $cartItem->setUserCart($userCart);
                $cartItem->setSize($size);
                $cartItem->setQuantity($quantity);
                $em->persist($cartItem);
                
            }

            $em->flush();

            $this->addFlash('success', 'Produit ajouté au panier !');
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('product/details.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }


    #[Route('/cart/update/{id}', name: 'cart_update', methods: ['POST'])]
    public function update(CartList $cartItem, Request $request, EntityManagerInterface $em): Response
    {
        $size = $request->request->get('size');
        $quantity = (int) $request->request->get('quantity');
    
        if ($size !== null) {
            $cartItem->setSize($size);
        }
    
        $cartItem->setQuantity($quantity);
    
        $em->flush();
    
        $this->addFlash('success', 'Votre panier a été mis à jour.');
        return $this->redirectToRoute('cart_index');
    }
    

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(CartList $item, EntityManagerInterface $em): Response
    {
        $em->remove($item);
        $em->flush();

        $this->addFlash('success', 'Produit retiré du panier.');
        return $this->redirectToRoute('cart_index');
    }
}