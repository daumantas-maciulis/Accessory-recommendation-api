<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductModel
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findProductsByCategory(string $categoryName): ?array
    {
        $itemsInCategory = $this->entityManager->getRepository(Product::class)->findBy(["productCategory" => $categoryName]);

        $twoSelectedItems = [];
        foreach (array_rand($itemsInCategory, 2) as $item) {
            array_push($twoSelectedItems, $itemsInCategory[$item]);
        }

        if (!$itemsInCategory) {
            return null;
        }
        return $twoSelectedItems;
    }
}

