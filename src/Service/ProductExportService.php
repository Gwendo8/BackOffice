<?php
namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function exportToCSV(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['name', 'description', 'price'], ',', '"', '\\');            
            $products = $this->productRepository->findAll();

            foreach ($products as $product) {
                fputcsv($output, [
                    $product->getName(),
                    $product->getDescription(),
                    $product->getPrice(),
                ], ',', '"', '\\');
            }

            fclose($output);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');
        
        return $response;
    }
}
?>