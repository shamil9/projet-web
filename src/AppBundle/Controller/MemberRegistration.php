<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Member;
use AppBundle\Form\MemberRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MemberRegistration extends BaseController
{
    /**
     * @Route("membre/nouveau", name="member_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new Member();
        $form = $this->createForm(MemberRegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRegistrationDate();
            
            $this->em()->persist($user);
            $this->em()->flush();


            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/member_register_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
