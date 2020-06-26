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

        /*         $url = 'https://oauth2.googleapis.com/token?client_id='.$this->googleClient.'&client_secret='.$this->googleId.'&code='.$code.'&redirect_uri='.$redirectUri.'&grant_type=authorization_code';

                $response = $this->httpClient->request('POST', $url, [
                    'headers' => [
                        'Accept' => 'application/json',
                    ]
                ]); */

        //get json config to use in the request
        $response = $this->httpClient->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
        $openIdConfig = json_decode($response->getContent());
        // set the request
        $response = $this->httpClient->request('POST', $openIdConfig->userinfo_endpoint, [
            'headers' => [
                'code' => $code,
                'client_id' => $this->googleClient,
                'client_secret' => $this->googleId,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code'
            ]
        ]);
        $accessToken = json_decode($response->getContent())->access_token;
        if($accessToken){ 
            $response = $this->httpClient->request('GET', $openIdConfig->userinfo_endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '. $accessToken
                ]
            ]);
            $jsonResponse = json_decode($response->getContent());
            if($jsonResponse->email_verified === true){
                dd($jsonResponse->email);
            }
        }
        else
        {
            throw new NotFoundHttpException("Un problème est survenu, veuillez réessayer.");
        }


        dd($response->toArray());
        
    }
}