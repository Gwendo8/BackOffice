<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $product = new Product();
        $product->setName("Basket puma");
        $product->setDescription("Voici l'une des dernières basket de la marque puma.");
        $product->setPrice(54.99);

        $this->assertEquals("Basket puma", $product->getName());
        $this->assertEquals("Voici l'une des dernières basket de la marque puma.", $product->getDescription());

        $this->assertSame(54.99, $product->getPrice());
    }
}