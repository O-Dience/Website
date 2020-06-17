<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/marque/{id}/modifier", name="brand_edit", requirements={"id": "\d+"}, methods={"GET","POST"})
     */
    public function brandEdit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $form->get('password')->getData();
            if ($password != null)
            {
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/influenceur/liste", name="influencer_list", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function listInfluencers()
    {
        
        $influencers = $this->getDoctrine()->getRepository(User::class)->findByRole('["ROLE_INFLUENCER"]');

        return $this->render('user/influencer/list.html.twig', [
            "influencers" => $influencers,
        ]);
    }

    /**
     * @Route("/influenceur/{id}/annonces", name="influencer_announcements", methods={"GET"})
     */
    public function listOfAnnouncements($id)
    {
        $influencer = $this->getDoctrine()->getRepository(User::class)->find($id);
        $announcements = $this->getDoctrine()->getRepository(Announcement::class)->findByUserId($id);
        return $this->render('user/influencer/announcements.html.twig', [
            'influencer' => $influencer,
            'announcements' => $announcements
        ]);
    }

}
