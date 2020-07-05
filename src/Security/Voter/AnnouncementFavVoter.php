<?php

namespace App\Security\Voter;

use App\Entity\AnnouncementFav;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnouncementFavVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['delete'])
            && $subject instanceof AnnouncementFav;
    }

    protected function voteOnAttribute($attribute, $announcementFav, TokenInterface $token)
    {
        $user = $token->getUser();

         // if the user is anonymous, do not grant access
         if (!$user instanceof UserInterface) {
            return false;
        }

         // ... (check conditions and return true to grant permission) ...
         switch ($attribute) {
        case 'delete':
            if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $announcementFav->getUser()) {
                return true;
            }
            break;
    }

    return false;
}
}