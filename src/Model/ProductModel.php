<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
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

    public function findProductsByCategory(string $categoryName): array
    {
        return $this->productRepository->findBy(["productCategory" => $categoryName]);
    }

}