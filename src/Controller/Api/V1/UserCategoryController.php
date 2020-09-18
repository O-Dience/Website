<?php

namespace App\Controller\Api\V1;

use App\Repository\UserCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api/v1/user/category", name="api_v1_user_category_")
 */
class UserCategoryController extends AbstractController
{

    public function __construct(UserCategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $userCategory = $this->categoryRepo->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userCategory);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Centre d\'intérêt supprimé'], Response::HTTP_NO_CONTENT);
    }
}