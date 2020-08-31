<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UserSocialRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleUserProvider
{
    private $googleClient;
    private $googleId;
    private $httpClient;

    /**
     * @param  $googleClient
     * @param  $googleId
     * @param  $httpClient
     * @param  $generator
     */
    public function __construct($googleClient, $googleId, HttpClientInterface $httpClient, UrlGeneratorInterface $generator, ImageUploader $imageUploader, UserRepository $userRepository, UserSocialRepository $userSocialRepository)
    {
        $this->googleClient = $googleClient;
        $this->googleId = $googleId;
        $this->httpClient = $httpClient;
        $this->generator = $generator;
        $this->imageUploader = $imageUploader;
        $this->userRepository = $userRepository;
        $this->userSocialRepository = $userSocialRepository;
    }

    // Get Google API Token and inject information in User entity
    public function loadUserFromGoogle(string $code)
    {

        $redirectUri = $this->generator->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);

        //get json config to use in the request
        $response = $this->httpClient->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
        $openIdConfig = json_decode($response->getContent());
        $userInfo = $openIdConfig->userinfo_endpoint;
        // set the request
        $response = $this->httpClient->request('POST', $openIdConfig->token_endpoint, [
            'body' => [
                'code' => $code,
                'client_id' => $this->googleClient,
                'client_secret' => $this->googleId,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code'
            ]
        ]);
        
        $accessToken = json_decode($response->getContent())->access_token;
        
        if($accessToken){ 
            $response = $this->httpClient->request('GET', $userInfo, [
                'headers' => [
                    'Authorization' => 'Bearer '. $accessToken
                ]
            ]);
            $jsonResponse = json_decode($response->getContent());
            if($jsonResponse->email_verified === true){

                //Check if user already exist in database
                $user = $this->userRepository->findOneByEmail($jsonResponse->email);
                
                 if($user){
                    return $user;
                }
                // Otherwise, create partial user with google API data
                else {
                    $user = new User();
                    $user->setUsername($jsonResponse->given_name);
                    $user->setEmail($jsonResponse->email);
                    $user->setPicture($this->imageUploader->getAvatarFromUrl($jsonResponse->picture));
                    return $user;
                }
            }
        }
        else
        {
            throw new NotFoundHttpException('404');
        }
    }


        // Get All needed information from a Youtube user
        public function getYoutubeProfile(User $user)
        {
            // Get corresponding userSocial object
            $userSocial = $this->userSocialRepository->findOneBy([
                'user' => $user,
                'social' => 1 // 1 = Youtube Id
                ]);

            if($userSocial){
                $youtubeLink = $userSocial->getLink();
            } else {
                return null;
            }

        }
}