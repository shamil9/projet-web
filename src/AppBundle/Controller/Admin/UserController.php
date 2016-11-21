<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Form\MemberEditType;
use AppBundle\Form\ProMemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
* Gestion des utilisateurs
*/
class UserController extends BaseController
{
    /**
     * Gestion des utilisateurs
     *
     * @Route("/admin/users", name="admin_users")
     * @return Response
     */
    public function indexAction()
    {
        // $this->adminCheck();

        $proMembers = $this->getRepository('AppBundle:ProMember')->findAll();
        $members = $this->getRepository('AppBundle:Member')->findAll();

        return $this->render('admin/users/index.html.twig', compact('proMembers', 'members'));
    }

    /**
     * Modification du profil d'utilisateur
     *
     * @Route("/admin/users/{user}/edit", name="admin_users_edit")
     * @param  User $user
     * @return Response
     */
    public function editAction(User $user, Request $request)
    {
        // $this->adminCheck();

        if ($user instanceof ProMember) {
            $form = $this->createForm(ProMemberEditType::class, $user);
        }

        if ($user instanceof Member) {
            $form = $this->createForm(MemberEditType::class, $user);
        }

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

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Suppression de l'utilsateur
     *
     * @Route("admin/users/{user}/destroy", name="admin_users_destroy")
     * @param  Request $request
     * @param  User    $user
     * @return Response
     */
    public function destroyAction(Request $request, User $user)
    {
        // $this->adminCheck();
        $this->deleteUser($request, $user);

        return $this->redirect('/admin');
    }

    /**
     * Bannir un utilisateur
     *
     * @Route("/admin/users/{user}/ban", name="admin_users_ban")
     *
     * @param  User   $user
     * @return Response
     */
    public function blockAction(User $user)
    {
        $user->setIsActive(0);

        $this->em()->persist($user);
        $this->em()->flush();

        return $this->redirect('/admin/users');
    }
}
