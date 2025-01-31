<?php
// src/Security/UserVoter.php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const ADD = 'add';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie si l'attribut est valide et que le sujet est une instance de User
        return in_array($attribute, [self::ADD, self::EDIT, self::DELETE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Vérifie que l'utilisateur est connecté
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Vérifie si l'utilisateur est un administrateur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true; // L'administrateur peut tout faire
        }

        // Restrictions pour les actions de modification ou suppression
        switch ($attribute) {
            case self::ADD:
                return false; // Par exemple, les utilisateurs ne peuvent pas ajouter de nouveaux utilisateurs
            case self::EDIT:
            case self::DELETE:
                // L'utilisateur peut modifier ou supprimer uniquement son propre compte
                return $user === $subject;
        }

        return false;
    }
}
?>