<?php

namespace App\Controller;

use Exception;
use Instagram\Api;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_dashboard', ['id'=>$this->getUser()->getId()]);
        }
        return $this->render('main/homepage.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /** 
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig', [
        ]);
    }

    /**
     * Undocumented function
     *
     *@Route("/insta", name="contact")
     * @return void
     */
    public function Insta(UrlGeneratorInterface $generator,SessionInterface $session)
    {
        //https://github.com/thephpleague/oauth2-instagram
        $redirectUri = $generator->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL); 
        $provider = new Instagram([
            'clientId'          => '619667588741979',
            'clientSecret'      => 'e5e22df772a687f180c99460f14134e7',
            'redirectUri'       => 'https://localhost:8000/insta',
            'host'              => 'https://api.instagram.com',  // Optional, defaults to https://api.instagram.com
            'graphHost'         => 'https://graph.instagram.com' // Optional, defaults to https://graph.instagram.com
        ]);
        
        if (!isset($_GET['code'])) {
            
           
            
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
           
            //$_SESSION['oauth2state'] = $provider->getState();
            $session->set('oauth2state', $provider->getState());
            
            header('Location: '.$authUrl);
            exit;
        
        // Check given state against previously stored one to mitigate CSRF attack
        //} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            //dd($_GET, $session->get('oauth2state'));
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $session->get('oauth2state'))) {
        
            
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        
        } else {
        
          
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
        
            
            // Optional: Now you have a token you can look up a users profile data
            try {
        
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                // Use these details to create a new profile
                printf('Hello %s!', $user->getNickname());
        
            } catch (Exception $e) {
        
                // Failed to get user details
                exit('Oh dear...');
            }
        
            // Use this to interact with an API on the users behalf
            dd($token->getToken());
        }

       

    }

}
