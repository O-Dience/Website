<?php

namespace App\Service;

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
    public function __construct($googleClient, $googleId, HttpClientInterface $httpClient, UrlGeneratorInterface $generator)
    {
        $this->googleClient = $googleClient;
        $this->googleId = $googleId;
        $this->httpClient = $httpClient;
        $this->generator = $generator;
    }

    // Get Google API Token and inject information in User entity
    public function loadUserFromGoogle(string $code)
    {
        $redirectUri = $this->generator->generate('app_register_influencer', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $url = 'https://oauth2.googleapis.com/token?client_id='.$this->googleClient.'&client_secret='.$this->googleId.'&code='.$code.'&redirect_uri='.$redirectUri.'&grant_type=authorization_code';

        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        
        $token = $response->toArray()['access_token'];
        dd($response->toArray());
        if($token){
            
            $userKey = $response->toArray()["id_token"];
            $response = $this->httpClient->request('GET', 'https://www.googleapis.com/gmail/v1/users/me/profile' , [
                'headers' => [
                    'Authorization' => 'Bearer '.$token
                ]
            ]);
        }else{
            throw new NotFoundHttpException("Un problème est survenu, veuillez réessayer.");
        }


        dd($response->toArray());
        
    }
}