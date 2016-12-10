<?php

namespace AppBundle\EventListener;

use AppBundle\Event\EmailNotification;

/**
* Enovi d'email pour différents actions
*/
class EmailNotificationListener
{
    private $mailer;
    private $twig;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function onGlobalContact(EmailNotification $event)
    {
        $request = $event->request;
        $message = \Swift_Message::newInstance()
            ->setSubject($request->request->get('message'))
            ->setFrom($request->request->get('from'))
            ->setTo('contact@bien-etre.com')
            ->setBody(
                $this->twig->render('emails/contact.html.twig', [
                    'message' => $request->request->get('message'),
                    'from' => $request->request->get('from'),
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}