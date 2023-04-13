<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Generate 10 fake users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($faker->password)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setRoles(['ROLE_USER']); // Set roles as needed

            $manager->persist($user);
        }
        for($i=0; $i<10; $i++){
            $article = new Article();
            $article->setTitre($faker->words(3, true));
            $article->setContenu($faker->sentence(5));
            $article->setDate(new \DateTimeImmutable($faker->date('Y-m-d')));
            $manager->persist($article);
        }
        $manager->flush();

        $manager->flush();
        
    }
    
}

// Generate 10 fake articles
