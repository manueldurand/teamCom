<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;


class UserFixtures extends Fixture

{
    private $counter = 1;
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
        $this->faker = Faker\Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setNom('admin');
        $admin->setEmail('admin@mail.com');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <=5; $i++){
            $usr = new User();
            $usr->setNom($faker->firstName);
            $usr->setEmail($faker->email);
            $usr->setPassword(
                $this->passwordEncoder->hashPassword($usr, 'secret')
            );
            $manager->persist($usr);
            $this->addReference('usr-'.$this->counter, $usr);
            $this->counter++;
            
            
        }

        $manager->flush();
    }
}
