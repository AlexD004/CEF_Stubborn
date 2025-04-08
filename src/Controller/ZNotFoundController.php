<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ZNotFoundController extends AbstractController
{
    #[Route('/{slug}', name: 'not_found', requirements: ['slug' => '.*'])]
    public function notFound(): Response
    {
        return new Response($this->renderView('errors/404.html.twig'), Response::HTTP_NOT_FOUND);
    }
}
