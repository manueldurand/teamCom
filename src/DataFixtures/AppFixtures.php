<?php

namespace App\DataFixtures;

use App\Entity\TodoList;
use Faker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
   

    public function load(ObjectManager $manager): void
    {


        $description = 'nouvelle tache ';
        $comment = 'new comment ';
        
        for ($i = 0; $i <10; $i++){
            $todo = new TodoList();
            $todo->setDescription($description.$i);
            $todo->setComment($comment.$i);
            $manager->persist($todo);
            
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
