<?php


namespace AppBundle\Controller\Member;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberType;
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
        $form = $this->createForm(MemberType::class, $user = $this->getUser(), ['validation_groups' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            if (!is_null($request->files->get('member_edit')['picture'])) {
                $avatar = $this->get('app.image_storage_manager')->storeAvatarImage($user);
                $image = $this->get('app.image_manager')->make($avatar);

                $user->setPicture($image->createAvatar()->image->basename);
            }

            $this->em()->persist($user);
            $this->em()->flush();

            $this->log('User updated');
        }

        return $this->redirectToRoute('edit_profile');
    }
}
