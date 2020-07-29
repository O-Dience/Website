<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailProvider
{
    private $mailer;
    public function __construct(MailerInterface $mailer){
        $this->mailer= $mailer;
    }
    public function subscribtionEmail( $user)
    {
        $email = (new TemplatedEmail())
                ->from('contact.odience@gmail.com')
                ->to($user->getEmail())
                ->subject('Inscription confirmée')
                ->htmlTemplate('registration/influencer_email.html.twig');
            $this->mailer->send($email);
            

    }

}