<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\AnnouncementFav;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementFavRepository;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use App\Repository\SocialNetworkRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce", name="announcement_")
 */
class AnnouncementController extends AbstractController
{
    /**
     * @Route("/liste", name="list", methods={"GET"})
     */
    public function list(AnnouncementRepository $announcementRepository, CategoryRepository $categoryRepository, SocialNetworkRepository $socialNetworkRepository, Request $request): Response
    {
        $announcements = $announcementRepository->findAll();

        // If search is done, we try to find a match by title, then if no match, find by content
        $search = $request->query->get("search", null);
        if ($search){
            $announcements = $announcementRepository->searchByTitle($search);
            if(!$announcements){
                $announcements = $announcementRepository->searchByContent($search);
            }
        }

        $categories = $categoryRepository->findAll();
        $socialNetworks = $socialNetworkRepository->findAll();
        return $this->render('announcement/list.html.twig', [
            'announcements' => $announcements, 'categories'=>$categories, 'socialNetworks'=> $socialNetworks
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, ImageUploader $imageUploader): Response
    {
        $announcement = new Announcement();
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->getRandomFileName('jpg');
            if($imageUploader->moveFile($form->get('picto')->getData(), 'image_announcement')){
            $announcement->setImage($imageName);
            };

            $announcement->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announcement);
            $entityManager->flush();

            return $this->redirectToRoute('announcement_list');
        }

        return $this->render('announcement/new.html.twig', [
            'announcement' => $announcement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Announcement $announcement): Response
    {
        return $this->render('announcement/show.html.twig', [
            'announcement' => $announcement
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Announcement $announcement): Response
    {

        $this->denyAccessUnlessGranted('edit', $announcement);

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announcement_show', ['id'=> $announcement->getId()]);
        }

        return $this->render('announcement/edit.html.twig', [
            'announcement' => $announcement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Announcement $announcement): Response
    {
        $this->denyAccessUnlessGranted('delete', $announcement);
        
        if ($this->isCsrfTokenValid('delete'.$announcement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announcement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('announcement_index');
    }

    /**
     * 
     * add or remove an announcement to the favorites
     * 
     * @Route("/{id}/favoris", name="favorite")
     *
     * @param Announcement $announcement
     * @param ObjectManager $manager
     * @param AnnouncementFavRepository $favRepo
     * @return Response
     */
    public function favorites(Announcement $announcement, EntityManagerInterface $manager, AnnouncementFavRepository $favRepo): Response
    {
        $user =$this->getUser();

        if(!$user){

        return $this->json(['code'=>403, 'message'=>'Unauthorizer'], 403);

        }
        if ($announcement->isFavByUser($user)){
            $favorite = $favRepo->findOneBy([
                'announcement'=>$announcement,
                'user'=>$user
            ]);
            
            $manager->remove($favorite);
            $manager->flush();

            return $this->json(['code'=>200, 'message'=> 'L\'annonce '.  $announcement->getTitle() . ' a été retirée de vos favoris !'], 200);
        }

        $favorite = new AnnouncementFav();
        $favorite->setAnnouncement($announcement);
        $favorite->setUser($user);

        $manager->persist($favorite);
        $manager->flush();
        return $this->json(['code'=>200, 'message'=> 'L\'annonce '.  $announcement->getTitle() . ' a été ajoutée à vos favoris !'], 200);
    }
}
