<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\Sale;
use AppBundle\Entity\User;
use AppBundle\Repository\ProMemberRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProMemberController extends BaseController
{
    /**
     * @Route("/prestataires/{slug}", name="pro_user_profile")
     * @param Request $request
     * @param ProMember|User $user
     * @return string
     */
    public function showAction(Request $request, ProMember $user)
    {
        $favorites = [];
        if ($this->getUser() instanceof Member) {
            //Tableau avec les favoris
            $favorites = $this->getUser() ? $this->getUser()->getFavorites()->toArray() : [];
        }

        return $this->render('pro_member/show.html.twig', [
            'user' => $user,
            'favorite' => array_reduce($favorites, function($carry, Favorite $key) use ($user) {

                //Contrôle si le prestataire est déjà dans les favoris
                if ($key->getProMember() == $user->getId()) {
                    return $key->getProMember();
                }
                return 0;
            })
        ]);
    }

    /**
     * @Route("/prestataires", name="pro_users_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $users = $this->getRepository('AppBundle:ProMember')->findAll();

        return $this->render('pro_member/index.html.twig', ['users' => $users]);
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
            ->map(function (ProMember $item, $key) {
            return [
                'name' => $item->getName(),
                'id' => $item->getId(),
                'categ' => $item->getCategories()
            ];
        });

        return $this->json($suggestions);
    }

    /**
     * @Route("/sale/{sale}/pdf", name="pro_user_sale_pdf")
     * @param Sale $sale
     * @return Response
     */
    public function generateSalePdf(Sale $sale)
    {
        $html = $this->renderView(':pro_member:pdf.html.twig', array(
            'sale'  => $sale
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => "attachment; filename='{$sale->getName()}.pdf'"
            )
        );
    }
}
