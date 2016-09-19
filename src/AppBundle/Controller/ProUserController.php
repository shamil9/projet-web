<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProUserController extends BaseController
{
    /**
     * @Route("/prestataire/{user}", name="pro_index")
     * @param Request $request
     * @param User $user
     * @return string
     */
    public function showAction(Request $request, User $user)
    {
        return $this->render('ProUser/show.html.twig', ['user' => $user]);
    }
}
