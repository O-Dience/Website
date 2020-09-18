<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
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
    public function __construct(
        $instagramClient,
        $instagramSecret,
        HttpClientInterface $httpClient,
        UrlGeneratorInterface $generator,
        ImageUploader $imageUploader,
        UserRepository $userRepository,
        SessionInterface $session,
        EntityManagerInterface $em,
        Security $security
    ) {
        $this->instagramClient = $instagramClient;
        $this->instagramSecret =  $instagramSecret;
        $this->httpClient = $httpClient;
        $this->generator = $generator;
        $this->imageUploader = $imageUploader;
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->em = $em;
        $this->security = $security;
    }

    public function provider()
    {
        $provider = new Instagram([
            'clientId'          => $this->instagramClient,
            'clientSecret'      => $this->instagramSecret,
            'redirectUri'       => 'https://localhost:8000/insta',
            'host'              => 'https://api.instagram.com',  // Optional, defaults to https://api.instagram.com
            'graphHost'         => 'https://graph.instagram.com' // Optional, defaults to https://graph.instagram.com
        ]);

        return $provider;
    }

    public function urlGenerator()
    {
        //https://github.com/thephpleague/oauth2-instagram
        $provider = $this->provider();


        $authUrl = $provider->getAuthorizationUrl();

        return $authUrl;
    }

    public function loadUserFromInsta($code)
    {

        $provider = $this->provider();

        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);




        // Optional: Now you have a token you can look up a users profile data
        try {


            // We got an access token, let's now get the user's details
            $userData = $provider->getResourceOwner($token);

            $user = $this->userRepository->findOneByInstagramAccount($userData->getNickname());

            if ($user === null){
                $user = $this->security->getUser() ;
                $user->setInstagramAccount($userData->getNickfname());
                $this->em->persist($user);
                $this->em->flush();
            }
          
            return $user;

        } catch (Exception $e) {

           return false;

        }

        // Use this to interact with an API on the users behalf
        dd($token->getToken());
    }
}
