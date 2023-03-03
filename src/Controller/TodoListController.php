<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new (Request $request, EntityManagerInterface $em): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Todolist();
        $form = $this->createFormBuilder($task)
            ->add('description', TextType::class)
            ->add('comment', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $todo = new TodoList();
            $todo->setDescription($task->getDescription());
            $todo->setComment($task->getComment());
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('todo_list');

        }


        return $this->render('todo_list/new.html.twig', [
            'form' => $form->createView(),
            'data' => $task
        ]);
        

        
    }
    #[Route('/todolist/delete/{id}', methods:['GET' , 'DELETE'], name:'todo_delete')]
    public function delete(int $id, EntityManagerInterface $em ): Response
    {
       $task = $em->getRepository(TodoList::class)->find($id);
       $em->remove($task);
       $em->flush();

        return $this->redirectToRoute('todo_list');
    }

}
