<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\Security\Core\Security;

use App\Entity\User;
use App\Form\RegisterType;

final class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    //public function home(Security $security): Response
    public function home(): Response
    {
       /* $user = $security->getUser();

        if ($user) {
            return $this->render('home_logged.html.twig', [
                'user' => $user
            ]);
        }*/

        return $this->render('home.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}
