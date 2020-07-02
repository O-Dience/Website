<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InfluencerType;
use App\Form\BrandType;
use App\Service\ImageUploader;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function registerInfluencer(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader, MailerInterface $mailer): Response
    {
 
        $user = new User();
        $form = $this->createForm(InfluencerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "avatar_user");
            if($imageName){
                $user->setPicture($imageName);
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
            // do anything else you need here, like send an email

            $email = (new Email())
            ->from('contact.odience@gmail.com')
            ->to($user->getEmail())
            ->subject('Bienvenue sur O\'Dience')
            ->html('<p>Vous êtes bien inscrit !</p>');
            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_influencer.html.twig', [
            'influencerForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("marque", name="brand", methods={"GET", "POST"})
     */
    public function registerBrand(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(BrandType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If an image is uploaded, Image Uploader service is called to create a random unique file name and move image to the right folder
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "avatar_user");
            if($imageName){
                $user->setPicture($imageName);
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

            $email = (new Email())
            ->from('contact.odience@gmail.com')
            ->to($user->getEmail())
            ->subject('Bienvenue sur O\'Dience')
            ->html('<p>Vous êtes bien inscrit sur notre site !</p>');
            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_brand.html.twig', [
            'brandForm' => $form->createView(),
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
