<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api/v1/announcement/favs", name="api_v1_announcement_fav_")
 */
class AnnouncementFavController extends AbstractController
{
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
     * @Route("/{id}", name="delete", methods={ "DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'Fav supprimé',
            'path' => 'src/Controller/Api/V1/AnnouncementFavController.php',
        ]);
    }
}
