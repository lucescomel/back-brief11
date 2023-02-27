<?php
// src\DataFixtures\AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Books;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $livre = new Books;
            $livre->setTitle('Livre ' . $i);
            $manager->persist($livre);
        }

        $manager->flush();
    }
}