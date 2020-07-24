<?php

namespace App\Controller\Api\V1;

use App\Entity\UserSocial;
use App\Repository\UserSocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api/v1/user/social", name="api_v1_user_social_")
 */
class UserSocialController extends AbstractController
{

    public function __construct(UserSocialRepository $socialRepo)
    {
        $this->socialRepo = $socialRepo;
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $userSocial = $this->socialRepo->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userSocial);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Réseau supprimé'], Response::HTTP_NO_CONTENT);
    }
}
