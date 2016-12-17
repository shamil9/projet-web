<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Form\MemberType;
use AppBundle\Form\ProMemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     *
     * @return Response
     */
    public function indexAction()
    {
        $proMembers = $this->getRepository('AppBundle:ProMember')->findAll();
        $members = $this->getRepository('AppBundle:Member')->findAll();

        return $this->render('admin/users/index.html.twig', compact('proMembers', 'members'));
    }

    /**
     * Modification du profil d'utilisateur
     *
     * @Route("/admin/users/{user}/edit", name="admin_users_edit")
     *
     * @param  User   $user
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(User $user, Request $request)
    {
        if ($user instanceof ProMember) {
            $form = $this->createForm(ProMemberType::class, $user);
        }

        if ($user instanceof Member) {
            $form = $this->createForm(MemberType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $this->createAvatarImage($user);

            $this->em()->persist($user);
            $this->em()->flush();
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Suppression de l'utilisateur
     *
     * @Route("admin/users/{user}/destroy", name="admin_users_destroy")
     * @Method({"POST"})
     *
     * @param  Request $request
     * @param  User    $user
     *
     * @return Response
     */
    public function destroyAction(Request $request, User $user)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_user_destroy_token', $token)) {
            $this->em()->remove($user);
            $this->em()->flush();
        }

        return $this->redirect('/admin');
    }

    /**
     * Bannir un utilisateur
     *
     * @Route("/admin/users/{user}/ban", name="admin_users_ban")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param  User   $user
     *
     * @return Response
     */
    public function blockAction(Request $request, User $user)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_user_ban_token', $token)) {
            if ($user->getIsActive()) {
                $user->setIsActive(0);
            } else {
                $user->setIsActive(1);
            }

            $this->em()->persist($user);
            $this->em()->flush();
        }

        return $this->redirect('/admin/users');
    }
}
