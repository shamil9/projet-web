<?php

namespace AppBundle\Controller;

use AppBundle\Event\EmailNotification;
use AppBundle\Form\ContactFormType;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $regions = $this->getRepository('AppBundle:ProMember')->findCitiesWithProUsers();
        $categories = $this->getRepository('AppBundle:Category')->findAll();
        $slides = $this->getRepository('AppBundle:Image')->findBy(['type' => 'admin-slider']);

        return $this->render('default/index.html.twig', [
            'regions' => $regions,
            'categories' => $categories,
            'sliderImages' => $slides,
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
     * Affichage du choix entre enregistrement comme utilisateur ou prestataire
     *
     * @Route("/inscription", name="register")
     */
    public function registerAction()
    {
        if ($this->getUser()) {
            return $this->redirect('/');
        }
        return $this->render('default/register.html.twig');
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new EmailNotification($request);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('global.contact', $event);

            return $this->redirect('/');
        }

        return $this->render('default/contact.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Affiche la liste de tous les stages
     *
     * @Route("/stages", name="stages_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workshopsAction(Request $request)
    {
        $workshops = $this->em()->getRepository('AppBundle:Workshop')->getAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $workshops,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('default/workshops.html.twig', [
            'workshops' => $pagination,
        ]);
    }

    /**
     * Moteur de recherche
     *
     * @Route("/s", name="search")
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundataion\Response
     */
    public function searchAction(Request $request)
    {
        $userName = $request->get('prestataire');
        $city = $request->get('ville');
        $category = $request->get('categorie');
        $results = $this->getRepository('AppBundle:ProMember')->search($userName, $city, $category);

        return $this->render('default/search.html.twig', [
            'results' => $results,
        ]);
    }
}
