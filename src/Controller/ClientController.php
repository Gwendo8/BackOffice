<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\Voter\ClientVoter;

final class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(ClientRepository $clientRepository): Response
    {

        $clients = $clientRepository->findAllClient();
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/add', name: 'app_client_add')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(ClientVoter::ADD);

        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCreatedAt(new \DateTimeImmutable());

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/edit/{id}', name: 'app_client_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Client $client): Response
    {
        $this->denyAccessUnlessGranted(ClientVoter::EDIT, $client);
        

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = $form->get('createdAt')->getData();

            if ($createdAt) {
                if (!$createdAt instanceof \DateTimeImmutable) {
                    $createdAt = \DateTimeImmutable::createFromMutable($createdAt);
                }
                $client->setCreatedAt($createdAt);
            } else {
                $client->setCreatedAt(new \DateTimeImmutable()); 
            }

            $em->flush();

            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client
        ]);
    }
    #[Route('/client/delete/{id}', name: 'app_client_delete')]
    public function delete($id, EntityManagerInterface $entityManager ,Client $client): Response
    {
        $this->denyAccessUnlessGranted(ClientVoter::DELETE, $client);

        $client = $entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        $entityManager->remove($client);
        $entityManager->flush();

        $this->addFlash('success', 'Client supprimé avec succès');
        return $this->redirectToRoute('app_client');
    }
}
