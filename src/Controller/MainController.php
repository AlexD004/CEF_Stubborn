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

  /*  #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('admin.html.twig');
    }*/
}
