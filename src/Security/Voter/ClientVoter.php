<?php
namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ClientVoter extends Voter
{
    const ADD = 'ADD';
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::ADD, self::EDIT, self::DELETE])
            && $subject instanceof Client || $subject === null;
    }

    protected function voteOnAttribute(string $attribute, $client, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; 
        }
        switch ($attribute) {
            case self::ADD:
                return $this->canAdd($user);
            case self::EDIT:
                return $this->canEdit($user, $client);
            case self::DELETE:
                return $this->canDelete($user, $client);
        }

        return false;
    }

    private function canAdd(User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_MANAGER', $user->getRoles());
    }

    private function canEdit(User $user, Client $client): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_MANAGER', $user->getRoles());
    }

    private function canDelete(User $user, Client $client): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_MANAGER', $user->getRoles());
    }
}
?>