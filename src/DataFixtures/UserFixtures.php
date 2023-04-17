<?php

namespace App\DataFixtures;

use App\Entity\TodoList;
use App\Entity\User;
use App\Entity\ViewPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;


class UserFixtures extends Fixture

{
    private $counter = 1;
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
        
    }
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadTodo($manager);
    }
    public function loadUser(ObjectManager $manager): void
    {
        $users = [];

        $supadmin = new User();
        $supadmin->setNom('supadmin');
        $supadmin->setEmail('supadmin@mail.com');
        $supadmin->setPassword(
            $this->passwordEncoder->hashPassword($supadmin, 'supadmin')
        );
        $supadmin->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($supadmin);

        $admin = new User();
        $admin->setNom('admin');
        $admin->setEmail('admin@mail.com');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH']);
        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <=10; $i++){
            $usr = new User();
            $usr->setNom($faker->firstName);
            $usr->setEmail($faker->email);
            $usr->setPassword(
                $this->passwordEncoder->hashPassword($usr, 'secret')
            );
            $manager->persist($usr);
            $users[] = $usr;
            $this->addReference('usr-'.$this->counter, $usr);
            $this->counter++;   
        }

        

        $manager->flush();
    }

    public function loadTodo(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $description = 'nouvelle tache ';
        $comment = 'new comment ';
        
        for ($i = 0; $i <10; $i++){
            $todo = new TodoList();
            $todo->setDescription($description.$i);
            $todo->setComment($comment.$i);
            $todo->setUser($this->getReference('usr-'.rand(1,5)));
            $manager->persist($todo);
            
        }
        for ($i = 0; $i <100; $i++){
            $todo = new TodoList();
            $todo->setDescription($faker->sentence);
            $todo->setComment($faker->words(3, true));
            $todo->setUser($this->getReference('usr-'.rand(1,10)));
            $manager->persist($todo);

            for($j = 0;$j < mt_rand(0, 10); $j++) {
                $view = new ViewPost();
                $view->setPost($todo);
                $view->setUser($this->getReference('usr-'.rand(1,10)));
                $manager->persist($view);

    
            }
            
        }

        $manager->flush();
    }

}
