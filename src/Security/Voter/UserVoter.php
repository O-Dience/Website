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
        return in_array($attribute, ['show_influencer', 'show_brand', 'show_social', 'add_social','edit', 'dashboard'])
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
            case 'show_influencer':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_BRAND', $user->getRoles()) || $user == $userToCompare) {
                    return true;
                }
                break;
            case 'show_brand':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_INFLUENCER', $user->getRoles()) || $user == $userToCompare) {
                    return true;
                }
                break;
            case 'show_social':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userToCompare) {
                    return true;
                }
                break;
                case 'add_social':
                    if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userToCompare) {
                        return true;
                    }
                    break;
            case 'edit':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userToCompare) {
                    return true;
                }
                break;
            case 'dashboard':
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $userToCompare) {
                    return true;
                }
                break;
        }

        return false;
    }
}
