<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

final class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(Security $security): Response
    {
       /* $user = $security->getUser();

        if ($user) {
            return $this->render('home_logged.html.twig', [
                'user' => $user
            ]);
        }*/

        return $this->render('home.html.twig');
    }

    #[Route('/{slug}', name: 'not_found', requirements: ['slug' => '.*'])]
    public function notFound(): Response
    {
        return new Response($this->renderView('errors/404.html.twig'), Response::HTTP_NOT_FOUND);
    }
}
