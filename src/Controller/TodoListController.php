<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\TodoList;
use App\Entity\ViewPost;
use App\Form\TodoListType;
use App\Form\UpdateFormType;
use App\Repository\TodoListRepository;
use App\Repository\ViewPostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    #[Route('/todolist', name: 'todo_list')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $doctrine->getRepository(TodoList::class)->findAllPaginationQuery(),
            $request->query->getInt('page', 1), /*page number*/
            15 /* nombre par page*/ 
        );

        if  ($this->isGranted('IS_AUTHENTICATED_FULLY')){    
            $user = $this->getUser();
            $username = $user->getNom();
        } else {
            $username = '';
            $user = '';
        }
        

        return $this->render('todo_list/index.html.twig', [
            'todo_list' => $pagination,
            'nom' => $username,
            'user' => $user,
            
        ]);
    }
    /**
     * @var App\Entity\User $user
     * 
     */
    #[Route('/todolist/new', name: 'new_todo')]
    #[IsGranted('ROLE_USER')]
    public function new (Request $request, EntityManagerInterface $em): Response
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
            $todo->setDescription($task->getDescription());
            $todo->setComment($task->getComment());
            $todo->setUser($user);
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_list', [
                'user' => $user,
            ]);

        }


        return $this->render('todo_list/new.html.twig', [
            'form' => $form->createView(),
            'data' => $task
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
            $todo->setCommentUser($user);
            $todo->setDateReponse(new \DateTime);
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
        $todolist = $doctrine->getRepository(TodoList::class)->findAllByUser($user);

        return $this->render('todo_list/account.html.twig', [
            'user' => $user,
            'todolist' => $todolist,
        ]);

    }

    #[Route('/todolist/users', name: 'users')]
    #[IsGranted('ROLE_ADMIN', message:"Vous n'avez pas accès à cette page")]
    public function showUsers(ManagerRegistry $doctrine){
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('todo_list/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/todolist/show/{id}', name:'show')]
    public function showByUser(int $id, ManagerRegistry $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $username = $user->getNom();
        $todolistByUser = $doctrine->getRepository(TodoList::class)->find($id);
        return $this->render('todo_list/show.html.twig', [
            'todolist' => $todolistByUser,
            'user' => $user,
            'nom' => $username,
        ]);
    }
    /**
     * permet d'indiquer qu'un post-todo a été vu ou non grace à une requete ajax
     */
    #[Route('/todolist/{id}', name: 'todo_like')]
    public function view(TodoList $todo, EntityManagerInterface $em, ViewPostRepository $viewpostRepo) : Response  
    {
        $user = $this->getUser();
        // si l'utilisateur n'est pas connecté
        if(!$user) {
          return $this->json([
            'code' => 403, 
            'message' => 'vous devez être connecté'
            , 403]);  
        } 
        if($todo->isViewedByUser($user)) {
            // si l'utilisateur a déjà cliqué, l'icone change pour indiquer un icone vide, comme non lu, la vue est supprimée de la bdd
            $view = $viewpostRepo->findOneBy([
                'post' => $todo,
                'user' => $user
            ]);
            $em->remove($view);
            $em->flush();
            return $this->json([
                'code' => 200,
                'message' => 'like supprimé',
                'views' => $viewpostRepo->count(['post' => $todo])
            ], 200);
        }

        // sinon on crée une nouvelle vue dans la bdd 
        $view = new ViewPost();
        $view->setPost($todo);
        $view->setUser($user);
        $em->persist($view);
        $em->flush();

        return $this->json([
            'code' => 200, 
            'message' => 'Like ajouté', 
            'views' => $viewpostRepo->count(['post' => $todo])
        ], 200);    
    }

}
