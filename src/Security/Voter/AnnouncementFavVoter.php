<?php

namespace App\Security\Voter;

use App\Entity\AnnouncementFav;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnouncementFavVoter extends Voter
{

    const delete = 'delete_announcementFav';

    protected function supports(string $attribute, $subject){
        
        return $attribute == self::delete && $subject instanceof AnnouncementFav;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        

        if(!$user instanceof User || !$subject instanceof AnnouncementFav){
            
            return false;
        }
        return $subject->getUser()->getId() === $user->getId();
    }
}