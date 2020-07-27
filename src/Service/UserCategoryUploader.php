<?php

namespace App\Service;

use App\Entity\UserCategory;
use App\Repository\UserCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class UserCategoryUploader
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function registerUserCategories(ArrayCollection $categories, $user)
    {
        foreach ($categories as $category) {
            $userCategory = new UserCategory();
            $userCategory->setCategory($category)->setUser($user);

            $this->em->getRepository(UserCategory::class);
            $this->em->persist($userCategory);
            $this->em->flush();
        }
    }
}
