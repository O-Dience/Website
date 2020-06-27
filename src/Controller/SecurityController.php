<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $googleClient;
    private $googleId;

    /**
     * @param $googleClient
     * @param $googleId
     */
    public function __construct($googleClient, $googleId)
    {
        $this->googleClient = $googleClient;
        $this->googleId = $googleId;
    }

    /**
     * @Route("/login/google", name="app_login_google")
     */
    public function googleAuth(UrlGeneratorInterface $generator)
    {
        $url = $generator->generate('app_register_influencer', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse('https://accounts.google.com/o/oauth2/v2/auth?scope=openid%20email%20profile&access_type=online&response_type=code&redirect_uri='. $url .'&client_id='.$this->googleClient);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

       

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
