<?php


namespace AppBundle\Controller\Member;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Form\Member\MemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends BaseController
{
    /**
     * Met à jour les données d'utilisateur
     *
     * @Route("/member/update", name="member_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $this->userCheck();

        /** @var Member $user */
        $form = $this->createForm(MemberEditType::class, $user = $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $this->createAvatarImage($form, $user);

            $this->em()->persist($user);
            $this->em()->flush();
        }

        return $this->redirect('/profil');
    }
}
