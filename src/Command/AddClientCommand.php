<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Console\Helper\QuestionHelper;

#[AsCommand(name: 'app:add-client', description: 'Ajoute un nouveau client')]
class AddClientCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $firstname = $helper->ask($input, $output, new Question('Prénom du client: '));

        $lastname = $helper->ask($input, $output, new Question('Nom du client: '));

        do {
            $email = $helper->ask($input, $output, new Question('Email du client: '));
            $violations = $this->validator->validate($email, [
                new Assert\NotBlank(),
                new Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")
            ]);

            if (count($violations) > 0) {
                $output->writeln('<error>' . $violations[0]->getMessage() . '</error>');
            }
        } while (count($violations) > 0);

        do {
            $phoneNumber = $helper->ask($input, $output, new Question('Numéro de téléphone du client: '));
            $violations = $this->validator->validate($phoneNumber, [
                new Assert\NotBlank(),
                new Assert\Regex(
                    pattern: "/^\+?[0-9\s\-]{7,15}$/",
                    message: "Le numéro de téléphone doit être valide (ex: +33612345678 ou 0612345678)."
                )
            ]);

            if (count($violations) > 0) {
                $output->writeln('<error>' . $violations[0]->getMessage() . '</error>');
            }
        } while (count($violations) > 0);

        $address = $helper->ask($input, $output, new Question('Adresse du client: '));

        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phoneNumber);
        $client->setAddress($address);
        $client->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>✅ Client ajouté avec succès !</info>');

        return Command::SUCCESS;
    }
}
