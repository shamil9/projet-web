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
    public function indexAction()
    {
        $newsletters = $this->getRepository('AppBundle:Newsletter')->findAll();
        return $this->render('newsletter/index.html.twig', [
            'newsletters' => $newsletters
        ]);
    }
}
