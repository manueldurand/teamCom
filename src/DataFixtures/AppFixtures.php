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
        $faker = Faker\Factory::create('fr_FR');

        $description = 'nouvelle tache ';
        $comment = 'new comment ';
        
        for ($i = 0; $i <10; $i++){
            $todo = new TodoList();
            $todo->setDescription($description.$i);
            $todo->setComment($comment.$i);
            $todo->setUser();
            $manager->persist($todo);
            
        }
        for ($i = 0; $i <10; $i++){
            $todo = new TodoList();
            $todo->setDescription($faker->sentence);
            $todo->setComment($faker->words(3, true));
            $manager->persist($todo);
            
        }

        $manager->flush();
    }
}
