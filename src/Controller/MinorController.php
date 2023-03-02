<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MinorController extends AbstractController
{
    #[Route('/minor', name: 'app_minor')]
    public function index(): Response
    {
        return $this->render('minor/index.html.twig', [
            'controller_name' => 'MinorController',
        ]);
    }
}
