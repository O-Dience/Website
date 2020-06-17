<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use App\Repository\SocialNetworkRepository;
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
    public function index(AnnouncementRepository $announcementRepository, CategoryRepository $categoryRepository, SocialNetworkRepository $socialNetworkRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $socialNetworks = $socialNetworkRepository->findAll();
        return $this->render('announcement/list.html.twig', [
            'announcements' => $announcementRepository->findAll(), 'categories'=>$categories, 'socialNetworks'=> $socialNetworks
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $announcement = new Announcement();
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



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
            'announcement' => $announcement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Announcement $announcement): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announcement_list');
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
        if ($this->isCsrfTokenValid('delete'.$announcement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announcement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('announcement_index');
    }
}
