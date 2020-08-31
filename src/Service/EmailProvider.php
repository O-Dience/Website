<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailProvider
{
    private $mailer;
    public function __construct(MailerInterface $mailer){
        $this->mailer= $mailer;
    }

        /**
     * Undocumented function
     *
     * @param [type] $token
     * @param [type] $to
     * @param [type] $username
     * @param [type] $tokenLifeTime
     * @param [type] $subject
     * @param [type] $template
     * @return void
     */
    public function sendMail($token, $to, $username,$tokenLifeTime, $subject, $template)
    {
        $email = (new TemplatedEmail())
        ->from(new Address('contact.odience@gmail.com'))
        ->to($to)
        ->subject($subject)
        ->htmlTemplate($template)
        ->context([
            'Token' => $token,
            'username' => $username,
            'tokenLifetime' => $tokenLifeTime,
        ]);

        $this->mailer->send($email);
    }

}


  


