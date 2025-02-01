<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Product;


class ProductVoter extends Voter
{
    public const ADD = 'product_add';
    public const EDIT = 'product_edit';
    public const DELETE = 'product_delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ADD, self::EDIT, self::DELETE]) 
            && ($subject instanceof Product || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
