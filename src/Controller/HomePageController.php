<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use symfony\component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class HomePageController extends AbstractController
{

    #[Route('/home/page', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }

    // #[Route('/register', name: 'register')]
    // public function register(): Response
    // {
    //     return $this->render('registerpage/index.html.twig', ['controller_name' => 'HomePageController',]);
    // }

    #[Route('/admin', 'admin')]
    public function admin(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('home_page/admin.html.twig', ['controller_name' => 'HomePageController',]);
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------
    // Permet de créer un utilisateur unique (test)
    // #[Route(path:"/insert", name:"home")]
    // function index (EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response {
    //     $user = new User();
    //     $user->setEmail('jane@gmail.com')->setRoles(['ROLE_ADMIN'])->setPassword($hasher->hashPassword($user,'0000'));
    //     $em->persist($user);
    //     $em->flush();
    //     return $this->render('home');
    // }

    //Premiere page de connexion 
    // #[Route('/login1', name: 'login')]
    // public function login(): Response
    // {
    //     return $this->render('loginpage/index.html.twig', ['controller_name' => 'HomePageController',]);
    // }


}
