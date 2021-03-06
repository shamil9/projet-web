<?php

namespace AppBundle\Controller\Member;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MemberRegistration extends BaseController
{
    /**
     * @Route("membre/inscription", name="member_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new Member();
        $form = $this->createForm(MemberType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRegistrationDate();

            $this->em()->persist($user);
            $this->em()->flush();

            // envoi d'email de confirmation
            $this->get('app.registration')->sendConfirmationMail($user);

            $this->log('User created');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/member_register_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
