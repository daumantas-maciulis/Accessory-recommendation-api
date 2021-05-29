<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use App\Exception\NoItemsInCategoryException;
use Doctrine\ORM\EntityManagerInterface;

class ProductModel
{
    private EntityManagerInterface $entityManager;
    private $productRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    public function findProductsByCategory(string $categoryName): ?array
    {
        $itemsInCategory = $this->productRepository->findBy(["productCategory" => $categoryName]);

        $twoSelectedItems = [];
        foreach (array_rand($itemsInCategory, 2) as $item) {
//            dump($item);
//            dump($itemsInCategory[$item]);
            array_push($twoSelectedItems, $itemsInCategory[$item]);
        }
//        dump($twoSelectedItems);


        if (!$itemsInCategory) {
            return null;
        }
        return $twoSelectedItems;
    }

}