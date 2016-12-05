<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\ProMember;
use AppBundle\Form\ProMemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProMemberRegistrationController
 * @package AppBundle\Controller
 */
class ProMemberRegistrationController extends BaseController
{

    /**
     * @Route("/prestataire/inscription", name="pro_member_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        $user = new ProMember();
        $form = $this->createForm(ProMemberType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRegistrationDate();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // envoi d'email de confirmation
            $this->get('app.registration')->sendConfirmationMail($user);

            return $this->redirectToRoute('homepage');
        }

        return $this->render(':registration:pro_member_register_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
