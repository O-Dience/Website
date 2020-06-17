<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function brandEdit(User $brand, Request $request, UserPasswordEncoderInterface $passwordEncoder):Response
    {
        $form = $this->createForm(UserType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $form->get('password')->getData();
            if ($password != null)
            {
                $encodedPassword = $passwordEncoder->encodePassword($brand, $password);
                $brand->setPassword($encodedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/influenceur/liste", name="influencer_list", methods={"GET"})
     */
    public function listInfluencers()
    {
        
        $influencers = $this->getDoctrine()->getRepository(User::class)->findByRole('["ROLE_INFLUENCER"]');

        return $this->render('user/influencer/list.html.twig', [
            "influencers" => $influencers,
        ]);
    }

    /**
     * @Route("/marque/{id}", name="brand_details", methods={"GET"})
     */
    public function brandDetails(User $brand): Response
    {
        
        $announcements = $this->getDoctrine()->getRepository(Announcement::class)->findByUserId($brand->getId());
        return $this->render('user/brand/show.html.twig', [
            'brand' => $brand,
            'announcements' => $announcements
        ]);
    }

}
