<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;

class RegistrationService
{
    private $mailer;
    private $twig;

    /**
     * RegistrationListener constructor.
     * @param \Swift_Mailer $mailer
     * @param $twig
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationMail(User $user)
    {
        $token = hash('sha256', $user->getUsername() . $user->getEmail());
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmez votre compte sur Bien-Être')
            ->setFrom('noreply@bien-etre.com')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('emails/confirmation.html.twig', [
                'user'  => $user,
                'token' => $token,
            ]), 'text/html');
        $this->mailer->send($message);

        $user->setConfirmationToken($token);
    }

    public function confirmUser(User $user, $token)
    {
        if ($token != $user->getConfirmationToken()) {
            return false;
        }

        $user->setIsActive(0);
        $user->setConfirmationToken(null);

        return true;
    }
}
