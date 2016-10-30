<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\Sale;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProMemberController extends BaseController
{
    /**
     * @Route("/prestataires/{slug}", name="pro_member_profile")
     * @param Request $request
     * @param ProMember $proMember
     * @return string
     * @internal param ProMember|User $user
     */
    public function showAction(Request $request, ProMember $proMember)
    {
        if ($this->getUser() instanceof Member) {
            $repo = $this->em()->getRepository('AppBundle:Favorite');

            //Recherche de prestataire dans le favoris
            $favorite = $repo->findOneBy([
                'proMember' => $proMember->getId(),
                'member' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('pro_member/show.html.twig', [
            'user' => $proMember,
            'favorite' => isset($favorite) ?: null,
        ]);
    }

    /**
     * @Route("/prestataires", name="pro_member_index")
     */
    public function listAction()
    {
        $users = $this->getRepository('AppBundle:ProMember')->findAll();

        return $this->render('pro_member/index.html.twig', ['users' => $users]);
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
     * @Route("/sale/{sale}/pdf", name="pro_member_sale_pdf")
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
        $message = \Swift_Message::newInstance()
            ->setSubject('Recommendation de prestataire')
            ->setFrom('noreply@bien-etre.com')
            ->setTo($request->get('mail'))
            ->setBody(
                $this->render('emails/commend-pro-user.html.twig', [
                    'message' => $request->get('message'),
                    'user' => $proMember,
                ]), 'text/html'
            );

        $this->get('mailer')->send($message);

        return $this->redirectToRoute('pro_member_profile', [
            'slug' => $proMember->getSlug()
        ]);
    }
}
