<?php

namespace App\DataFixtures;

use App\Entity\Commune;
use App\Entity\Media;
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
            if (mt_rand(0,1) === 1){
                $media = new Media();
                $media->setCommune($commune)
                    ->setImage($faker->imageUrl(640,480,'city'))
                    ->setVideo($faker->file('D:\projects\tmp','.\public\tmp'))
                    ->setArticle($faker->domainName);
                $manager->persist($media);
            }
            $manager->persist($commune);
        }

        $manager->flush();
    }
}