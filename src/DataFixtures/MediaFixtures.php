<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class MediaFixtures
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');


//        Create 10 users
        for ($i = 0; $i < 10; $i++) {

        }

        $manager->flush();
    }
}