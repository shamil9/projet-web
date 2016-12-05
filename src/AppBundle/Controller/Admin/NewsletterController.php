<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Newsletter;
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
        }

        return $this->redirect('/admin/newsletters');
    }

    /**
     * Supprimer une newsletter
     *
     * @Route("/admin/newsletters/{id}/destroy", name="admin_newsletters_destroy")
     * @param  Newsletter $newsletter
     * @return Response
     */
    public function destroyAction(Newsletter $newsletter)
    {
        $this->em()->remove($newsletter);
        $this->em()->flush();

        return $this->redirect('/admin/newsletters');
    }

    private function sendNewsletter($file)
    {
        $subscribers = $this->getRepository('AppBundle:NewsletterSubscriber')->findAll();

        foreach ($subscribers as $subscriber) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Bien Etre Newsletter')
                ->setFrom('bien@etre.com')
                ->setTo($subscriber->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/newsletter.html.twig',
                        [
                            'file' => 'http://bien-etre.com/assets/img/uploads/newsletters/' . $file,
                            'user' => $subscriber->getUser(),
                        ]
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }
    }
}
