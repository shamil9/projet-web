<?php

namespace AppBundle\Controller\Newsletter;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Newsletter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     */
    public function newAction( Request $request )
    {
        // TODO: Implement newAction() method.
    }

    /**
     * Affichage individuel
     * @param $newsletter Newsletter
     */
    public function showAction( $newsletter )
    {

    }

    /**
     * Edition
     */
    public function editAction()
    {
        // TODO: Implement editAction() method.
    }

    public function updateAction( Request $request )
    {
        // TODO: Implement updateAction() method.
    }

    /**
     * Suppression
     * @param Request $request
     */
    public function destroyAction( Request $request )
    {
        // TODO: Implement destroyAction() method.
    }
}
