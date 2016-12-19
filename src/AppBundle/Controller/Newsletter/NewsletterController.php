<?php

namespace AppBundle\Controller\Newsletter;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Member;
use AppBundle\Entity\Newsletter;
use AppBundle\Entity\NewsletterSubscriber;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsletterController extends BaseController
{
    /**
     * @Route("/newsletters", name="newsletters_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $newsletters = $this->getRepository('AppBundle:Newsletter')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $newsletters,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('newsletter/index.html.twig', [
            'newsletters' => $pagination,
        ]);
    }

    /**
     * Inscrit l'utilisateur à la newsletter
     *
     * @Route("newsletter/subscribe", name="newsletters_subscribe")
     * @return JsonResponse
     */
    public function subscribeAction()
    {
        $this->userCheck();

        try {
            $subscriber = new NewsletterSubscriber();
            /** @var Member $user */
            $user = $this->getUser();

            $subscriber->setUser($user);
            $this->em()->persist($subscriber);
            $this->em()->flush();

            return JsonResponse::create();
        } catch (Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }

    /**
     * Désinscrit l'utilisateur de la newsletter
     *
     * @Route("newsletter/unsubscribe", name="newsletters_unsubscribe")
     * @return JsonResponse
     */
    public function unsubscribeAction()
    {
        $this->userCheck();

        try {
            /** @var Member $user */
            $user = $this->getUser()->getSubscribed();
            $this->em()->getRepository('AppBundle:NewsletterSubscriber');
            $this->em()->remove($user);
            $this->em()->flush();

            return JsonResponse::create();
        } catch (Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }
}
