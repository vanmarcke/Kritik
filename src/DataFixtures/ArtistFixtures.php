<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;

class ArtistFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        // La fonction anonyme va être exécutée 50 fois
        $this->createMany(50, "artist", function($num){
            $artiste = new Artist;
            $nom = $this->faker->randomElement(["DJ ", "MC ", "Lil ", ""]);
            $nom .=$this->faker->firstName . " ";
            $nom .=$this->faker->randomElement([
                $this->faker->realText(10),
                "aka " . $this->faker->domainWord,
                "& the " . $this->faker->lastName,
                ""
            ]);

            $artiste->setName($nom)
                    ->setDescription($this->faker->realText(20));
            return $artiste;
        });

        $manager->flush();
    }
}
