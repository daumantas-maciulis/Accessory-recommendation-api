<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getProducts() as [$sku, $name, $price, $productGroup]) {
            $product = new Product();

            $product->setSku($sku);
            $product->setName($name);
            $product->setPrice($price);
            $product->setProductGroup($productGroup);

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getProducts()
    {
        return [
            ["WIN-N2", "Heavy duty winter boots", 399.99, "heavy-snow"],
            ["WIN-C3", "Down jacked", 234.2, "heavy-snow"],

            ["SN-O2", "Winter Jacked", 199.99, "snow"],
            ["PS-23", "Winter pants", 99.99, "snow"],

            ["SDK-23", "Rain Coat", 293, "heavy-rain"],
            ["SD63SD", "Rubber boots", 55, "heavy-rain"],

            ["UM-22", "Umbrella", 29, "rain"],
            ["GTX-2", "GORETEX shoes", 299.99, "rain"],

            ["CLD-22", "Jacket with SUN", 100, "clouds"],
            ["SCR-F", "Scarf from cold wind", 22, "clouds"],

            ["TAN-2", "Sun Tan cream", 9.99, "clear"],
            ["GLS-2", "Sun Glasses", 99.99, "clear"],

            ["RR-23", "Rubber jacket", 22, "fog"],
            ["PNTS", "Pants from rubber", 29, "fog"]
        ];
    }
}

