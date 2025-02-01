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
            // Ouvre un flux de sortie
            $output = fopen('php://output', 'w');
            // Ajouter l'en-tête CSV
            fputcsv($output, ['name', 'description', 'price']);
            
            // Récupérer tous les produits
            $products = $this->productRepository->findAll();

            // Ajouter chaque produit à la ligne CSV
            foreach ($products as $product) {
                fputcsv($output, [
                    $product->getName(),
                    $product->getDescription(),
                    $product->getPrice(),
                ]);
            }

            fclose($output);
        });

        // Définir le nom du fichier et les en-têtes HTTP pour l'exportation
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');
        
        return $response;
    }
}
?>