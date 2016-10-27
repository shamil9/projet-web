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
        $im = $this->get('app.media_manager');

        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function latestProUsersAction()
    {
        $users = $this->getRepository('AppBundle:ProMember')->findLatestProUsers();

        return $this->render('partials/_latestprousers.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigationRenderAction()
    {
        $regions = $this->getRepository('AppBundle:ProMember')->findCitiesWithProUsers();
        $user = $this->getUser();

        return $this->render('partials/_header.html.twig', [
            'regions' => $regions,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
    }    
    
    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * Affiche la liste de tous les stages
     *
     * @Route("/stages", name="stages_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workshopsAction()
    {
        $workshops = $this->em()->getRepository('AppBundle:Workshop')->getAll();

        return $this->render('default/workshops.html.twig', [
            'workshops' => $workshops,
        ]);
    }
}
