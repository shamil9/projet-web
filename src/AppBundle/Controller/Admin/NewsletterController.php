<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Newsletter;
use AppBundle\Event\EmailNotification;
use AppBundle\Form\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gestionde de newsletters
 */
class NewsletterController extends BaseController
{
    /**
     * Liste des newsletters
     *
     * @Route("/admin/newsletters", name="admin_newsletters")
     * @return Response
     */
    public function indexAction()
    {
        $newsletters = $this->getRepository('AppBundle:Newsletter')->findAll();
        $form = $this->createForm(NewsletterType::class);

        return $this->render('admin/newsletters/index.html.twig', [
            'newsletters' => $newsletters,
            'form'        => $form->createView(),
        ]);
    }

    /**
     * Ajouter une newsletter
     *
     * @Route("/admin/newsletters/create", name="admin_newsletters_create")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime('now');
            $newsletter->setPostedAt($date);
            $file = $newsletter->getDocument();
            $fileName = $date->format("d-m-y_H-i") . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('assets_root') . '/img/uploads/newsletters/',
                $fileName
            );
            $newsletter->setDocument($fileName);

            $this->sendNewsletter($fileName);
            $this->em()->persist($newsletter);
            $this->em()->flush();

            $this->log('Newsletter enregistrée');
        }

        return $this->redirect('/admin/newsletters');
    }

    /**
     * Supprimer une newsletter
     *
     * @Route("/admin/newsletters/{id}/destroy", name="admin_newsletters_destroy")
     * @param Request     $request
     * @param  Newsletter $newsletter
     * @return Response
     */
    public function destroyAction(Request $request, Newsletter $newsletter)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_newsletter_destroy_token', $token)) {
            $this->em()->remove($newsletter);
            $this->em()->flush();
        }

        $this->log('Newsletter supprimée');

        return $this->redirectToRoute('admin_newsletters');
    }

    private function sendNewsletter($file)
    {
        $subscribers = $this->getRepository('AppBundle:NewsletterSubscriber')->findAll();

        $dispatcher = $this->get('event_dispatcher');

        foreach ($subscribers as $subscriber) {
            $event = new EmailNotification(['user' => $subscriber->getUser(), 'file' => $file]);
            $dispatcher->dispatch('global.contact', $event);
        }
    }
}
