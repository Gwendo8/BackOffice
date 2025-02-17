<?php
namespace App\Tests\Service;

use App\Repository\ProductRepository;
use App\Service\ProductExportService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvTest extends TestCase
{
    public function testExportReturnsCsvResponse(): void
{
    /** @var ProductRepository|\PHPUnit\Framework\MockObject\MockObject */
    $productRepository = $this->createMock(ProductRepository::class);

    $productRepository->method('findAll')->willReturn([]);

    $csvExporter = new ProductExportService($productRepository);

    $response = $csvExporter->exportToCSV();

    $this->assertInstanceOf(StreamedResponse::class, $response);

    $this->assertEquals('text/csv', $response->headers->get('Content-Type'));

    ob_start();
    $response->sendContent();
    $output = ob_get_clean();

    $this->assertStringContainsString("name,description,price", $output);
}
}