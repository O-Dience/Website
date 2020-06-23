<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\User;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use App\Repository\SocialNetworkRepository;
use App\Service\ImageUploader;
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
        if ($search) {
            $announcements = $announcementRepository->searchByTitle($search);
            if (!$announcements) {
                $announcements = $announcementRepository->searchByContent($search);
            }
        }

        $categories = $categoryRepository->findAll();
        $socialNetworks = $socialNetworkRepository->findAll();
        return $this->render('announcement/list.html.twig', [
            'announcements' => $announcements, 'categories' => $categories, 'socialNetworks' => $socialNetworks
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
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "image_announcement");
            if ($imageName) {
                $announcement->setImage($imageName);
            };

            $announcement->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announcement);
            $entityManager->flush();

            return $this->redirectToRoute('user_dashboard', ['id' => $this->getUser()->getId()]);
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
    public function edit(Request $request, Announcement $announcement, ImageUploader $imageUploader): Response
    {

        $this->denyAccessUnlessGranted('edit', $announcement);

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "image_announcement");
            if ($imageName) {
                $announcement->setImage($imageName);
            };
            $announcement->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announcement_show', ['id' => $announcement->getId()]);
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

        if ($this->isCsrfTokenValid('delete' . $announcement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announcement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('announcement_index');
    }
}
