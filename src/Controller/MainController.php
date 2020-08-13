<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('main/homepage.html.twig', [] );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig', [] );
    }
}
