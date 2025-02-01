<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-products',
    description: 'Importe des produits à partir d’un fichier CSV.',
)]
class ImportProductsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('csvFile', InputArgument::REQUIRED, 'products.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFile = $input->getArgument('csvFile');

        if (!file_exists($csvFile)) {
            $io->error("Le fichier '$csvFile' n'existe pas.");
            return Command::FAILURE;
        }

        $io->info("Importation des produits depuis '$csvFile'...");

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0); 

        $records = $csv->getRecords();

        $count = 0;
        foreach ($records as $record) {
            if (empty($record['name']) || empty($record['description']) || !is_numeric($record['price'])) {
                $io->warning("Ligne ignorée (valeurs invalides) : " . json_encode($record));
                continue;
            }

            $product = new Product();
            $product->setName($record['name']);
            $product->setDescription($record['description']);
            $product->setPrice((float) $record['price']);

            $this->entityManager->persist($product);
            $count++;
        }

        $this->entityManager->flush();
        $io->success("$count produits importés avec succès !");

        return Command::SUCCESS;
    }
}
?>