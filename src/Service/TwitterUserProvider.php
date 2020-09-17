<?php

namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\User;
use App\Service\ImageUploader;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TwitterUserProvider
{
    private $twitterConsumerKey;
    private $twitterSecretKey;
    private $twitterCallback;

    public function __construct(
        $twitterConsumerKey,
        $twitterSecretKey,
        $twitterCallback,
        UserRepository $userRepository,
        SessionInterface $session,
        EntityManagerInterface $em,
        ImageUploader $imageUploader
    ) {
        $this->twitterConsumerKey = $twitterConsumerKey;
        $this->twitterSecretKey = $twitterSecretKey;
        $this->twitterCallback = $twitterCallback;
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->imageUploader = $imageUploader;
        $this->em = $em;
    }



    public function urlConstructor()
    {

        $oauth = new TwitterOAuth($this->twitterConsumerKey, $this->twitterSecretKey);
        $requestToken = $oauth->oauth('oauth/request_token', ['oauth_callback' => $this->twitterCallback]);
        $url = $oauth->url('oauth/authorize', array('oauth_token' => $requestToken['oauth_token']));

        return $url;
    }


    public function linkTwitterAccount($oauth_token, $oauth_verifier)
    {
       

        $consumer_key = $this->twitterConsumerKey;
        $consumer_secret = $this->twitterSecretKey;

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_verifier);
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        $this->session->set('oauth_token', $access_token['oauth_token']);
        $this->session->set('oauth_token_secret', $access_token['oauth_token_secret']);

        $params = array('tweet_mode' => 'extended', 'include_entities' => 'true', 'include_email' => 'true', 'skip_status' => 'true');
        $userData = $connection->get('account/verify_credentials', $params);

        

        
        $user = $this->userRepository->findOneByEmail($userData->email);
      
        $user->setTwitterAccount($userData->screen_name);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    
    

    
    }


    public function loadUserFromTwitter()
    {
 /* 
        // $consumer_key = $this->twitterConsumerKey;
        // $consumer_secret = $this->twitterSecretKey;

        // $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_verifier);
        // $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

        // $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);



        $consumer_key = $this->twitterConsumerKey;
        $consumer_secret = $this->twitterSecretKey;

        $access_token['oauth_token'] = $this->session->get('oauth_token');
        $access_token['oauth_token_secret'] = $this->session->get('oauth_token_secret');

        // dd($this->session->get('oauth_token'));

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        $params = array('tweet_mode' => 'extended', 'include_entities' => 'true', 'include_email' => 'true', 'include_entities' => 'true', 'skip_status' => 'true');
        $userData = $connection->get('account/verify_credentials', $params);


        $this->session->set('oauth_token', $access_token['oauth_token']);
        $this->session->set('oauth_token_secret', $access_token['oauth_token_secret']);

        $params = array('tweet_mode' => 'extended', 'include_entities' => 'true', 'include_email' => 'true', 'skip_status' => 'true');
        $userData = $connection->get('account/verify_credentials', $params);
        dd($userData);
        if ($userData->email == null) {
            return false;
        }

        $user = $this->userRepository->findOneByEmail($userData->email);
        if ($user == null) {

            return false;
        }

        return $user;
        */
        
    } 


}
