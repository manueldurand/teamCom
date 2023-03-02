<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    #[Route('/todolist', name: 'todo_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $todo_list = $doctrine->getRepository(TodoList::class)->findAll();

        return $this->render('todo_list/index.html.twig', [
            'todo_list' => $todo_list,
        ]);
    }

    #[Route('/todolist/new', name: 'new_todo')]
    public function new (Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Todolist();
        $task->setDescription('Write a blog post');
        $task->setComment('commencer...');
        $form = $this->createFormBuilder($task)
            ->add('description', TextType::class)
            ->add('comment', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();           

        return $this->render('todo_list/new.html.twig', [
            'form' => $form->createView(),
        ]);
        

        
    }


}
