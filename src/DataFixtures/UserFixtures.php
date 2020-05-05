<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;


class UserFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {   
        $this->createMany(10, "user_admin", function($num){
            $user = new User;
            $nom = "admin" . $num;
            $user->setEmail($nom . "@kritik.fr");
            $user->setPassword( password_hash($nom, PASSWORD_DEFAULT) );
            $user->setRoles(["ROLE_ADMIN"]);
            return $user;
        });

        $this->createMany(200, "user_user", function($num){
            $user = new User;
            $nom = "user" . $num;
            $user->setEmail($nom . "@mail.org");
            $user->setPassword( password_hash($nom, PASSWORD_DEFAULT) );
            $user->setRoles(["ROLE_USER"]);
            return $user;
        });       

        $manager->flush();
    }
}
