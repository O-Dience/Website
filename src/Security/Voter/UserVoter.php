<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['edit', 'dashboard'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $userToCompare, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'edit':
                if ($user == $userToCompare) {
                    return true;
                }
                break;
            case 'dashboard':
                if ($user == $userToCompare) {
                    return true;
                }
                break;
        }

        return false;
    }
}
