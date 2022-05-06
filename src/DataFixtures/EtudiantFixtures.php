<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EtudiantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0;$i<5;$i=$i+1){
            $etudiant = new Etudiant();
            $etudiant->setNom($faker->name);
            $etudiant->setPrenom($faker->firstName);
            $section = new Section();
            $section->setDesignation($faker->word);
            if($i%2==0){
                $section->addEtudiant($etudiant);
            }
            $manager->persist($etudiant);
            $manager->persist($section);
        }
        $manager->flush();
    }
}
