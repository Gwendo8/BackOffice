<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Product();
        $product1->setName('Sneakers Nike Air Max');
        $product1->setDescription('Chaussures de sport confortables avec un design moderne.');
        $product1->setPrice(120.99);  
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Baskets Adidas Ultraboost');
        $product2->setDescription('Baskets haute performance avec un excellent amorti.');
        $product2->setPrice(150.50);  
        $manager->persist($product2);

        $manager->flush();
    }
}
