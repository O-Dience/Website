<?php

namespace App\Controller\Api\V1;

use App\Repository\AnnouncementFavRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api/v1/announcement/favs", name="api_v1_announcement_fav_")
 */
class AnnouncementFavController extends AbstractController
{

    public function __construct(AnnouncementFavRepository $favRepo)
    {
        $this->favRepo = $favRepo;
    }
    /**
     * @Route("", name="liste", methods={"GET"})
     */
    public function liste()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/V1/AnnouncementFavController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $announcementFav = $this->favRepo->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($announcementFav);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Favoris supprimé'], Response::HTTP_NO_CONTENT);
    }


}

