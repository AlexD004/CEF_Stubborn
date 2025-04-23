<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\CartAddType;
use Doctrine\ORM\EntityManagerInterface;

final class ProductController extends AbstractController
{

    #[Route('/admin', name: 'dashboard')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }

                $product->setImage($newFilename);
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès !');

            return $this->redirectToRoute('product_add');
        }

        // GET all products in DB
        $products = $em->getRepository(Product::class)->findAll();

        // New form for each product
        $editForms = [];
        foreach ($products as $product) {
            $editForms[$product->getId()] = $this->createForm(ProductType::class, $product, [
                'action' => $this->generateUrl('product_edit', ['id' => $product->getId()]),
                'is_edit' => true
            ])->createView();
        }

        return $this->render('admin.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'editForms' => $editForms
        ]);
    }

    // UPDATE Products with form in /admin
    #[Route('/admin/product/{id}/edit', name: 'product_edit', methods: ['POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $action = $request->request->get('action');

            if ($action === 'edit') {
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('uploads_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    }

                    $product->setImage($newFilename);
                }

                $em->flush();
                $this->addFlash('success', 'Produit modifié !');

            } elseif ($action === 'delete') {
                $em->remove($product);
                $em->flush();
                $this->addFlash('success', 'Produit supprimé !');
            }
        }

        return $this->redirectToRoute('product_add');
    }


    // GET featured products
    #[Route('/_fragment/produits-a-la-une', name: 'products_featured_fragment')]
    public function featuredBlock(EntityManagerInterface $em): Response
    {
        $featured = $em->getRepository(Product::class)->findBy(['isFeatured' => true]);

        return $this->render('product/_featured_block.html.twig', [
            'products' => $featured
        ]);
    }

    // GET one product by ID
    #[Route('/product/{id}', name: 'product_details')]
    public function productDetails(Product $product, Request $request): Response
    {
        $form = $this->createForm(CartAddType::class, null, [
            'action' => $this->generateUrl('cart_add', ['id' => $product->getId()])
        ]);

        return $this->render('product/details.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    // GET All products with filter by price (range)
    #[Route('/products', name: 'products_all')]
    public function all(Request $request, EntityManagerInterface $em): Response
    {
        // Get 'price range type' (low, mid, high) in request
        $priceRange = $request->query->get('price');

        if (!$priceRange) {
            $priceRange = 'all';
        }

        $repo = $em->getRepository(Product::class);

        switch ($priceRange) {
            case 'low':
                $products = $repo->createQueryBuilder('p')
                    ->where('p.price BETWEEN :min AND :max')
                    ->setParameter('min', 10)
                    ->setParameter('max', 30)
                    ->getQuery()
                    ->getResult();
                break;

            case 'mid':
                $products = $repo->createQueryBuilder('p')
                    ->where('p.price BETWEEN :min AND :max')
                    ->setParameter('min', 30)
                    ->setParameter('max', 35)
                    ->getQuery()
                    ->getResult();
                break;

            case 'high':
                $products = $repo->createQueryBuilder('p')
                    ->where('p.price BETWEEN :min AND :max')
                    ->setParameter('min', 35)
                    ->setParameter('max', 50)
                    ->getQuery()
                    ->getResult();
                break;

            default:
                $products = $repo->findAll();
                break;
        }

        return $this->render('product/all.html.twig', [
            'products' => $products,
            'currentFilter' => $priceRange
        ]);
    }
    
}
