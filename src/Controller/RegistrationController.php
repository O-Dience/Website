<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserDefaultType;
use App\Service\EmailProvider;
use App\Service\ImageUploader;
use App\Service\UserCategoryUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/inscription/", name="app_register_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("influenceur", name="influencer", methods={"GET", "POST"})
     */
    public function registerInfluencer(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader, UserCategoryUploader $categoryUploader, EmailProvider  $subscribeEmail): Response
    {

        $user = new User();
        $form = $this->createForm(UserDefaultType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "avatar_user");
            if ($imageName) {
                $user->setPicture($imageName);
            } else {
                //Let's attribute a random pic to our new user
                $picture = 'default/default' . rand(1, 13) . '.png';
                $user->setPicture($picture);
            };

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(["ROLE_INFLUENCER"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Set Usercategories without notification settings
            $categoryUploader->registerUserCategories($form->get('categories')->getData(), $user);

            $subscribeEmail->subscribtionEmail($user);
            
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_user.html.twig', [
            'form' => $form->createView(),
            // pass formVar variable to help rendering right form variation
            'formVar' => 'influencer'
        ]);
    }

    /**
     * @Route("marque", name="brand", methods={"GET", "POST"})
     */
    public function registerBrand(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader, UserCategoryUploader $categoryUploader, EmailProvider $email): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('user_dashboard', ['id' => $this->getUser()->getId()]);
        }
        $user = new User();
        $form = $this->createForm(UserDefaultType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "avatar_user");
            if ($imageName) {
                $user->setPicture($imageName);
            } else {
                //Let's attribute a random pic to our new user
                $picture = 'default/default-brand.png';
                $user->setPicture($picture);
            };

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(["ROLE_BRAND"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            // Set Usercategories without notification settings
            $categoryUploader->registerUserCategories($form->get('categories')->getData(), $user);

            $email->subscribtionEmail($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_user.html.twig', [
            'form' => $form->createView(),
            // pass formVar variable to help rendering right form variation
            'formVar' => 'brand'
        ]);
    }

    /**
     * @Route("question", name="oauthUser", methods={"GET", "POST"})
     */
    public function registerOauthUser()
    {

        // TODO: CREATE FORM TO HANDLE COMPLETION OF OAUTH USERS REGISTRATION
    }
}
