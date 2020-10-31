<?php

namespace App\Controller;

use App\Service\InstagramUserProvider;
use Exception;
use Instagram\Api;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    

}
