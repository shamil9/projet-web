<?php


namespace AppBundle\Controller\Newsletter;


use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Entity\NewsletterSubscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewsletterSubscriptionController extends BaseController
{
    /**
     * Inscrit l'utilisateur à la newsletter
     *
     * @Route("newsletter/subscribe", name="newsletters_subscribe")
     * @return JsonResponse
     */
    public function subscribeAction()
    {
        try {
            $subscriber = new NewsletterSubscriber();
            /** @var Member $user */
            $user = $this->getUser();

            $subscriber->setUser( $user );
            $this->em()->persist( $subscriber );
            $this->em()->flush();

            return JsonResponse::create();
        } catch ( Exception $e ) {
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
        try {
            /** @var Member $user */
            $user = $this->getUser()->getSubscribed();
            $this->em()->getRepository( 'AppBundle:NewsletterSubscriber' );
            $this->em()->remove( $user );
            $this->em()->flush();

            return JsonResponse::create();
        } catch (Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }
}
