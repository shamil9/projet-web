<?php

namespace AppBundle\Controller;

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

        return $this->render('default/index.html.twig', [
            'regions' => $regions,
            'categories' => $categories,
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
            $message = \Swift_Message::newInstance()
                ->setSubject($form->getData()['message'])
                ->setFrom($form->getData()['from'])
                ->setTo('contact@bien-etre.com')
                ->setBody(
                    $this->render('emails/contact.html.twig', [
                        'message' => $form->getData()['message'],
                        'from' => $form->getData()['from'],
                    ]),
                    'text/html'
                );

            $this->get('mailer')->send($message);

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
    public function workshopsAction()
    {
        $workshops = $this->em()->getRepository('AppBundle:Workshop')->getAll();

        return $this->render('default/workshops.html.twig', [
            'workshops' => $workshops,
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
