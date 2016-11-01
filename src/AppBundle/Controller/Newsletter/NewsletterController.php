<?php

namespace AppBundle\Controller\Newsletter;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class NewsletterController extends BaseController implements CrudInterface
{
    /**
     * @Route("/newsletters", name="newsletters_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $newsletters = $this->getRepository('AppBundle:Newsletter')->findAll();
        return $this->render('newsletter/index.html.twig', [
            'newsletters' => $newsletters
        ]);
    }

    /**
     * Nouveau enregistrement
     */
    public function newAction()
    {
        // TODO: Implement newAction() method.
    }

    /**
     * Affichage individuel
     */
    public function showAction()
    {
        // TODO: Implement showAction() method.
    }

    /**
     * Edition
     */
    public function editAction()
    {
        // TODO: Implement editAction() method.
    }

    public function updateAction()
    {
        // TODO: Implement updateAction() method.
    }

    /**
     * Suppression
     */
    public function destroyAction()
    {
        // TODO: Implement destroyAction() method.
    }
}
