<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProUserController extends BaseController
{
    /**
     * @Route("/prestataire/{user}", name="pro_user_profile")
     * @param Request $request
     * @param User $user
     * @return string
     */
    public function showAction(Request $request, ProMember $user)
    {
        return $this->render('ProUser/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/prestataires", name="pro_users_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $users = $this->getRepository('AppBundle:ProMember')->findAll();

        return $this->render('ProUser/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/suggestions/{user}", name="pro_users_suggestions")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showSuggestions(User $user)
    {
        $query = $this->getRepository('AppBundle:ProMember')->findProUserSuggestions($user);
        $suggestions = $this->collection($query)
            ->map(function (User $item, $key) {
            return [
                'name' => $item->getName(),
                'id' => $item->getId(),
                'categ' => $item->getCategories()
            ];
        });

        return $this->json($suggestions);
    }
}
