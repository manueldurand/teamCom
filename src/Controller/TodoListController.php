<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\TodoList;
use App\Form\TodoListType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    #[Route('/todolist', name: 'todo_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $todo_list = $doctrine->getRepository(TodoList::class)->findAll();
        if  ($this->isGranted('IS_AUTHENTICATED_FULLY')){
            

            $user = $this->getUser()->getNom();
        } else {
            $user = '';
        }
        

        return $this->render('todo_list/index.html.twig', [
            'todo_list' => $todo_list,
            'nom' => $user,
            
        ]);
    }

    #[Route('/todolist/new', name: 'new_todo')]
    public function new (Request $request, EntityManagerInterface $em): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Todolist();
        $form = $this->createForm(TodoListType::class, $task);
    
        
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
    #[Route('/todolist/delete/{id}', name:'todo_delete')]
    public function delete(int $id, EntityManagerInterface $em, Request $request ): Response
    {

       $submittedToken = $request->request->get('token');
       if ($this->isCsrfTokenValid('delete-item', $submittedToken))
       {
        $task = $em->getRepository(TodoList::class)->find($id);
        $em->remove($task);
        $em->flush();

       }
        
       
        return $this->redirectToRoute('todo_list');
    }

}
