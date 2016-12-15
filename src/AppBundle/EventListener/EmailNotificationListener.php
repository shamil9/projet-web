<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\ProMember;
use AppBundle\Event\EmailNotification;
use Symfony\Bridge\Monolog\Logger;

/**
* Enovi d'email pour différents actions
*/
class EmailNotificationListener
{
    private $mailer;
    private $twig;
    private $logger;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * Message depuis le formulaire de contact principal
     *
     * @param  EmailNotification $event
     */
    public function onGlobalContact(EmailNotification $event)
    {
        $form = $event->params['request']->request->get('contact_form');
        $message = \Swift_Message::newInstance()
            ->setSubject($form['message'])
            ->setFrom($form['from'])
            ->setTo('contact@bien-etre.com')
            ->setBody(
                $this->twig->render('emails/contact.html.twig', [
                    'message' => $form['message'],
                    'from' => $form['from'],
                ]),
                'text/html'
            );

        $this->mailer->send($message);

        $this->logger->info('Main contact form email');
    }

    /**
     * Message de recommendation de prestataire
     *
     * @param  EmailNotification $event
     */
    public function onProMemberRecommendation(EmailNotification $event)
    {
        $form = $event->params['request'];
        $message = \Swift_Message::newInstance()
            ->setSubject('Recommendation de prestataire')
            ->setFrom('noreply@bien-etre.com')
            ->setTo($form->get('mail'))
            ->setBody(
                $this->twig->render('emails/commend-pro-user.html.twig', [
                    'message' => $form->get('message'),
                    'user'    => $event->params['user'],
                ]),
                'text/html'
            );

        $this->mailer->send($message);

        $this->logger->info('Pro Member recommendation email');
    }

    /**
     * Message de contact de prestataire
     *
     * @param  EmailNotification $event
     */
    public function onProMemberContact(EmailNotification $event)
    {
        $form = $event->params['request']->request->get('contact_form');
        $user = $event->params['user'];

        $message = \Swift_Message::newInstance()
            ->setSubject($form['message'])
            ->setFrom($form['from'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('emails/contact-pro-user.html.twig', [
                    'user'    => $user,
                    'message' => $form['message'],
                    'from'    => $form['from'],
                ]),
                'text/html'
            );

        $this->mailer->send($message);

        $this->logger->info('Pro Member contact email');
    }

    public function onCategorySubmission(EmailNotification $event)
    {
        /** @var ProMember $user */
        $user = $event->params['user'];
        $category = $event->params['category'];

        $message = \Swift_Message::newInstance()
            ->setSubject('Nouvelle suggestion de catégorie')
            ->setFrom($user->getEmail())
            ->setTo('admin@bien-etre.com')
            ->setBody(
                $this->twig->render('emails/category-submission.html.twig', [
                    'user'    => $user,
                    'category' => $category,
                ]),
                'text/html'
            );

        $this->mailer->send($message);

        $this->logger->info($user->getUsername() . ' suggested category ' . $category->getName());
    }
}
