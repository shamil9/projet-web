<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
