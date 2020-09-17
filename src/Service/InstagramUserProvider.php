<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramUserProvider
{
    private $instaClient;
    private $instaId;
    
    private $httpClient;

    /**
     * @param  $googleClient
     * @param  $googleId
     * @param  $httpClient
     * @param  $generator
     */
    public function __construct($instagramClient, $instagramSecret, HttpClientInterface $httpClient, UrlGeneratorInterface $generator, ImageUploader $imageUploader, UserRepository $userRepository)
    {
        $this->instagramClient = $instagramClient;
        $this->instagramSecret =  $instagramSecret;
        $this->httpClient = $httpClient;
        $this->generator = $generator;
        $this->imageUploader = $imageUploader;
        $this->userRepository = $userRepository;
    }

    // Get Google API Token and inject information in User entity
    public function loadUserFromInsta(UrlGeneratorInterface $generator,SessionInterface $session)
    {
        //https://github.com/thephpleague/oauth2-instagram
        $redirectUri = $generator->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL); 
        $provider = new Instagram([
            'clientId'          => $this->instagramClient,
            'clientSecret'      => $this->instagramSecret,
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
                return $user; 
        
            } catch (Exception $e) {
        
                // Failed to get user details
                exit('Oh dear...');
            }
        
            // Use this to interact with an API on the users behalf
            dd($token->getToken());
        }

       

    }
}