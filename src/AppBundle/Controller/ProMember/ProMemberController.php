<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Event\EmailNotification;
use AppBundle\Form\ContactFormType;
use AppBundle\Form\ProMemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProMemberController extends BaseController
{
    /**
     * Affichage de la liste
     *
     * @Route("/prestataires", name="pro_member_index")
     */
    public function indexAction(Request $request)
    {
        $users = $this->getRepository('AppBundle:ProMember')->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pro_member/index.html.twig', ['users' => $pagination]);
    }

    /**
     * Affichage du profil
     *
     * @Route("/prestataires/{slug}", name="pro_user_profile")
     * @ParamConverter("user", class="AppBundle:ProMember")
     * @param ProMember $user
     * @return Response
     */
    public function showAction($user)
    {
        if ($this->getUser() instanceof Member) {
            $repo = $this->em()->getRepository('AppBundle:Favorite');

            //Recherche de prestataire dans le favoris
            $favorite = $repo->findOneBy([
                'proMember' => $user->getId(),
                'member'    => $this->getUser()->getId(),
            ]);
        }

        return $this->render('pro_member/show.html.twig', [
            'user'     => $user,
            'favorite' => $favorite ?? null,
        ]);
    }

    /**
     * @Route("/pro-member/update", name="pro_member_update")
     * @param Request $request
     * @return mixed
     */
    public function updateAction(Request $request)
    {
        /** @var ProMember $user */
        $user = $this->getUser();
        $form = $this->createForm(ProMemberType::class, $user, ['validation_groups' => 'edit']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            if (!is_null($request->files->get('pro_member')['picture'])) {
                $avatar = $this->get('app.image_storage_manager')->storeAvatarImage($user);
                $image = $this->get('app.image_manager')->make($avatar);

                $user->setPicture($image->createAvatar()->basename);
            }

            $this->em()->persist($user);
            $this->em()->flush();
        }

        return $this->redirect('/profil');
    }

    /**
     * @Route("/suggestions/{user}", name="pro_member_suggestions")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showSuggestions(User $user)
    {
        $query = $this->getRepository('AppBundle:ProMember')->findProUserSuggestions($user);
        $suggestions = $this->collection($query)
            ->map(function (ProMember $item) {
                return [
                    'name'    => $item->getName(),
                    'picture' => $item->getPicture(),
                    'slug'    => $item->getSlug(),
                ];
            });

        return $this->json($suggestions);
    }

    /**
     * Envoie la recommandation d'un prestataire
     *
     * @Route("/prestataires/{slug}/commend", name="pro_member_commend")
     * @param Request $request
     * @param ProMember $proMember
     * @return Response|static
     */
    public function recommendAction(Request $request, ProMember $proMember)
    {
        $event = new EmailNotification(['request' => $request, 'user' => $proMember]);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('promember.recommendation', $event);

        return $this->redirectToRoute('pro_user_profile', [
            'slug' => $proMember->getSlug(),
        ]);
    }

    /**
     * Contacter le prestataire
     *
     * @Route("/contact/{id}", name="user_contact")
     * @param ProMember $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function contactAction(ProMember $user, Request $request)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new EmailNotification(['request' => $request, 'user' => $user]);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('promember.contact', $event);

            return $this->redirectToRoute('pro_user_profile', ['slug' => $user->getSlug()]);
        }

        return $this->render('pro_member/partials/_contact-form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
