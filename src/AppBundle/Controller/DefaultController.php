<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function latestProUsersAction()
    {
        $users = $this->getRepository('AppBundle:User')->findLatestProUsers();

        return $this->render('partials/_latestprousers.html.twig', [
            'users' => $users
        ]);
    }

    public function navigationRenderAction()
    {
        $regions = '';

        return $this->render('partials/_header.html.twig', [
            'regions' => $regions
        ]);
    }
}
