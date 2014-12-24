<?php
// src/Kev/UserBundle/DataFixtures/ORM/LoadUser.php

namespace Kev\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kev\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listNames = array('Alex', 'Marine', 'Anna');

        foreach ($listNames as $name) {
            $user = new User;

            $user->setUsername($name);
            $user->setPassword($name);

            // On ne se sert pas du sel pour l'instant
            $user->setSalt('');
            // On définit uniquement le role ROLE_USER qui est le role de base
            $user->setRoles(array('ROLE_USER'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}