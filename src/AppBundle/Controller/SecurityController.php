<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    /**
     * @Route("/connexion", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirect('/');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        //récupération de l'erreur si existe
        $error = $authenticationUtils->getLastAuthenticationError();

        //dernier nom d'utilisateur fourni
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
