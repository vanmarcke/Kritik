<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Record;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecordFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(100, "record", function($num){
            $record = (new Record)->setTitle($this->faker->domainWord)
                                  ->setDescription($this->faker->realText(20))
                                  ->setReleaseAt($this->faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'))
                                  ->setArtist($this->getRandomReference("artist"))
                                  ;


            return $record;
        });

        $manager->flush();
    }

    public function getDependencies(){
        // Je définie quelles fixtures doivent être lancées avant la fixture actuelle
        return [ ArtistFixtures::class ];
    }

}
