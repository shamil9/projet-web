<?php

namespace AppBundle\Controller\Newsletter;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Newsletter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class NewsletterController extends BaseController
{
    /**
     * @Route("/newsletters", name="newsletters_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $newsletters = $this->getRepository('AppBundle:Newsletter')->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $newsletters,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('newsletter/index.html.twig', [
            'newsletters' => $pagination
        ]);
    }
}
