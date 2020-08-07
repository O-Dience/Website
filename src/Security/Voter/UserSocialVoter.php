<?php

namespace App\Security\Voter;

use App\Entity\UserSocial;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSocialVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['edit', 'delete'])
            && $subject instanceof UserSocial;
    }
    
    protected function voteOnAttribute($attribute, $userSocial, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'edit':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userSocial->getUser()) {
                    return true;
                }
                break;
            case 'delete':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userSocial->getUser()) {
                    return true;
                }
            break;
        }

        return false;
    }
}