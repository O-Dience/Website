<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/marque", name="brand")
     */
    public function brand()
    {

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/marque/{id}/modifier", name="brand_edit", requirements={"id": "\d+"}, methods={"GET","POST"})
     */
    public function brandEdit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
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

            return $this->redirectToRoute('brand');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
