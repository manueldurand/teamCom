<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\TodoList;
use App\Form\TodoListType;
use App\Form\UpdateFormType;
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
            
            $user = $this->getUser();
            $username = $user->getNom();
        } else {
            $username = '';
            $user = '';
        }
        

        return $this->render('todo_list/index.html.twig', [
            'todo_list' => $todo_list,
            'nom' => $username,
            'user' => $user,
            
        ]);
    }
    /**
     * @var App\Entity\User $user
     */
    #[Route('/todolist/new', name: 'new_todo')]
    public function new (Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Todolist();
        $form = $this->createForm(TodoListType::class, $task);
        $user = $this->getUser();
        $username = $user->getNom();
        dump($username);
    
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $username = $user->getNom();
            $task = $form->getData();
            $todo = new TodoList();
            $todo->setAuteur($user->getNom());
            $todo->setDescription($task->getDescription());
            $todo->setAuteur($auteur);
            $todo->setComment($task->getComment());
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_list', [
                'user' => $user,
            ]);

        }


        return $this->render('todo_list/new.html.twig', [
            'form' => $form->createView(),
            'data' => $task,
            'auteur' => $auteur,
        ]);
        
    }

    #[Route('/todolist/update/{id}', name: 'update_todo')]
    public function update_todo(int $id, Request $request, EntityManagerInterface $em)
    {
        $todo = $em->getRepository(TodoList::class)->find($id);
        $updateForm = $this->createForm(UpdateFormType::class, $todo);

        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid()) {
            $user = $this->getUser();
            $username = $user->getNom();
            $updateTask = $updateForm->getData();
            $todo->setReponse($updateTask->getReponse());
            $todo->setAuteurReponse($username);
            $em->persist($todo);
            $em->flush();
            return $this->redirectToRoute('todo_list', [
                'user' => $user,
            ]);
        }
        return $this->render('todo_list/update.html.twig',[
            'todo' => $todo,
            'form' => $updateForm->createView(),
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

    #[Route('/todolist/account', name: 'account')]
    public function account(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        return $this->render('todo_list/account.html.twig', [
            'user' => $user,
        ]);

    }

}
