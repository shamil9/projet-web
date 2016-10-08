<?php


namespace AppBundle\Controller;


use AppBundle\Entity\ProMember;
use AppBundle\Form\ProMemberRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProMemberRegistrationController
 * @package AppBundle\Controller
 */
class ProMemberRegistrationController extends BaseController
{

    /**
     * @Route("/prestataires/nouveau", name="pro_memer_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new ProMember();
        $form = $this->createForm(ProMemberRegistrationType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRegistrationDate();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('homepage');
        }
        
        return $this->render(':registration:pro_member_register_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
