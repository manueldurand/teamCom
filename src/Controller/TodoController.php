<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{

    #[Route('/todo', name: 'todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
            if (!$session->has('todos')){
              $todos = [
                'achat' => 'café',
                'maison' => 'lessive',
                'job' => 'finaliser les notes'
            ];  
            $session->set('todos', $todos);
            $this->addFlash('info', "la liste a été initialisée");
            }
            
        

        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('todos')){
            $session->get('todos');
            ;
        } else {
            $this->addFlash('error', "la liste des todos est absente");

        }
        return $this->redirectToRoute('todo');
    }
}
