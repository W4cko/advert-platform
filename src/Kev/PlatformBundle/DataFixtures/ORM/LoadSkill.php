<?php
// src/Kev/PlatformBundle/DataFixtures/ORM/LoadSkill.php

namespace Kev\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kev\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de compétences à ajouter
        $names = array('PHP', 'Symfony2', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');


        foreach ($names as $name) {
            // On crée la catégorie
            $skill = new Skill();
            $skill->setName($name);

            // On la persiste
            $manager->persist($skill);
        }

        // On déclenche l'enregistrement de toutes les competences
        $manager->flush();
    }
}