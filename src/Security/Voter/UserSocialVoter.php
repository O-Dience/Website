<?php

namespace App\Security\Voter;

use App\Entity\UserSocial;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSocialVoter extends Voter
{

    const edit = 'edit_userSocial';
    protected function supports(string $attribute, $subject){
        
        return $attribute == self::edit && $subject instanceof UserSocial;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        

        if(!$user instanceof User || !$subject instanceof UserSocial){
            
            return false;
        }
        return $subject->getUser()->getId() === $user->getId();
    }
}