<?php

namespace App\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $twitterConsumerKey;
    private $twitterSecretKey;

    public function __construct($twitterConsumerKey, $twitterSecretKey)
    {
        $this->twitterConsumerKey = $twitterConsumerKey;
        $this->twitterSecretKey = $twitterSecretKey;
    }
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(SessionInterface $session)
    {

        $consumer_key = $this->twitterConsumerKey;
        $consumer_secret = $this->twitterSecretKey;

        $access_token['oauth_token'] = $session->get('oauth_token');
        $access_token['oauth_token_secret'] = $session->get('oauth_token_secret');

        // dd($access_token);

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        $params = array('tweet_mode' => 'extended', 'include_entities' => 'true', 'include_email' => 'true', 'include_entities' => 'true', 'skip_status' => 'true');
        $userData = $connection->get('account/verify_credentials', $params);

        //dd($userData);


        if ($this->getUser()) {
            return $this->redirectToRoute('user_dashboard', ['id' => $this->getUser()->getId()]);
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
        return $this->render('main/contact.html.twig', []);
    }
}
