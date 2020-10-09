<?php

namespace App\DataFixtures;

use App\Entity\Commune;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommuneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $codepostaux = [];
        for ($j = 0; $j < $faker->numberBetween(1 ,5); $j++){
            array_push($codepostaux, $faker->postcode);
        }
//        Create 50 communes
        for ($i = 0; $i < 50; $i++) {
            $commune = new Commune();

            $commune->setCodesPostaux($codepostaux);
            $commune->setNom($faker->city);
            $commune->setCode($faker->postcode);
            $commune->setCodeDepartement($i + 5);
            $commune->setCodeRegion($i);
            $commune->setPopulation(random_int(50, 10000000));
            $manager->persist($commune);
        }

        $manager->flush();
    }
}