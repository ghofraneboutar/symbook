<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use function Symfony\Component\Clock\now;

class LivresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   $faker = Factory::create('fr_FR');
        for ($i = 1; $i < 100; $i++)
        {
            $livre = new Livre();
            $titre=$faker->name();
            $livre->setTitre($titre)->setSlug(strtolower(str_replace('','-',$titre)))
                ->setIsbn($faker->isbn13())
                ->setResume($faker->text)
                ->setEditeur($faker->company())
                ->setDateEdition($faker->dateTimeBetween('-5 years','now'))
                ->setImage("https://picsum.photos/200/300/?id=".$i)
                ->setPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max =70));
            $manager->persist($livre);
        }
        $manager->flush();
    }
}
