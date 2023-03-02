<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterController extends AbstractController
{
    #[Route('/master', name: 'app_master')]
    public function index(): Response
    {
        return $this->render('master/index.html.twig', [
            'name' => 'Durand',
            'firstname' => 'Manuel'
        ]);
    }

    #[Route("/master/{name}", name:"bonjour")]
    public function bonjour($name)
    {
        return $this->render('master/bonjour.html.twig', ['nom' => $name]);
    }
}
