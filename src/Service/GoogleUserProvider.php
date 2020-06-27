<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function __construct($googleClient, $googleId, HttpClientInterface $httpClient, UrlGeneratorInterface $generator, ImageUploader $imageUploader, UserRepository $userRepository)
    {
        $this->googleClient = $googleClient;
        $this->googleId = $googleId;
        $this->httpClient = $httpClient;
        $this->generator = $generator;
        $this->imageUploader = $imageUploader;
        $this->userRepository = $userRepository;
    }

    // Get Google API Token and inject information in User entity
    public function loadUserFromGoogle(string $code)
    {

        $redirectUri = $this->generator->generate('app_register_influencer', [], UrlGeneratorInterface::ABSOLUTE_URL);

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

                return $jsonResponse;
                //Check if user already exist in database
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $jsonResponse->email]);
                
                 if($user){

                    $user = $this->userRepository->findOneByEmail($jsonResponse->email);
                    return $user;
                }
                // Otherwise, create partial user and redirect to adapted form to register fully the new user
                else {

                    $user = new User;
                    $user
                        ->setUsername($jsonResponse->given_name)
                        ->setEmail($jsonResponse->email);
                        // ->setPicture($jsonResponse->picture);
                    return $user;
                }

            }
        }
        else
        {
            throw new NotFoundHttpException('404');
        }


        dd($response->toArray());
        
    }
}