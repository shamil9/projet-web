<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Form\MemberEditType;
use AppBundle\Form\ProMemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * Affiche la page du profil d'utilisateur selon son type
     *
     * @Route("/profil", name="edit_profile")
     * @var User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $this->userCheck();

        $user = $this->getUser();

        if ($user instanceof Member) {
            $form = $this->createForm(MemberEditType::class);
            $template = 'member/edit.html.twig';
        }

        if ($user instanceof ProMember) {
            $form = $this->createForm(ProMemberEditType::class);
            $template = 'pro_member/edit.html.twig';
        }

        return $this->render($template, [
            'user'   => $user,
            'form'   => $form->createView(),
            'errors' => null,
        ]);
    }

    /**
     * Supprime un utilisateur
     *
     * @Route("/supprimer", name="user_delete")
     * @param User $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function destroyAction(User $id, Request $request)
    {
        //suppression de l'utilisateur
        $this->deleteUser($request, $id);


        return $this->redirectToRoute('homepage');
    }
}
