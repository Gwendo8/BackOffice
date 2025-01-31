<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllUser();
        return $this->render('user/index.html.twig', [
            'users' => $users, // assure-toi que 'users' est utilisé ici
        ]);
    }

    #[Route('/user/add', name: 'app_user_add')]
    #[IsGranted('ROLE_ADMIN')] // Cette ligne permet d'assurer que seul un admin peut ajouter un utilisateur
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (empty($user->getPassword())) {
                $this->addFlash('error', 'Le mot de passe est requis');
                return $this->render('user/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            if ($form->isValid()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);

                $roles = $form->get('roles')->getData();
                $user->setRoles($roles);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Utilisateur ajouté avec succès');
                return $this->redirectToRoute('app_user');
            }
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    #[IsGranted('ROLE_ADMIN')] // Cette ligne permet d'assurer que seul un admin peut modifier un utilisateur
    public function edit($id, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $currentPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->getPassword())) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
            } else {
                $user->setPassword($currentPassword);
            }

            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    #[IsGranted('ROLE_ADMIN')] // Cette ligne permet d'assurer que seul un admin peut supprimer un utilisateur
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('app_user');
    }
}
?>