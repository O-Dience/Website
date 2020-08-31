<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UserSocialRepository;
use Google_Client;
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
    public function __construct($googleClient, $googleId, $googleJson, HttpClientInterface $httpClient, UrlGeneratorInterface $generator, ImageUploader $imageUploader, UserRepository $userRepository, UserSocialRepository $userSocialRepository)
    {
        $this->googleClient = $googleClient;
        $this->googleId = $googleId;
        $this->googleJson = $googleJson;
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
    public function getYoutubeChannel()
    {
        $client = new Google_Client();
        $client->setApplicationName('O\'Dience');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
        ]);
        
        // TODO: For this request to work, you must replace
        //       "YOUR_CLIENT_SECRET_FILE.json" with a pointer to your
        //       client_secret.json file. For more information, see
        //       https://cloud.google.com/iam/docs/creating-managing-service-account-keys
        $client->setAuthConfig($this->googleJson);
        $client->setAccessType('offline');
        
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();

        dd($authUrl);

        /* 
        printf("Open this link in your browser:\n%s\n", $authUrl);
        print('Enter verification code: ');
        
        $authCode = trim(fgets(STDIN));
        
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        $client->setAccessToken($accessToken);
        
        // Define service object for making API requests.
        $service = new Google_Service_YouTube($client);
        
        $queryParams = [
            'mine' => true
        ];
        
        $response = $service->channelSections->listChannelSections('snippet,contentDetails', $queryParams);
        dd($response); */

    }
}