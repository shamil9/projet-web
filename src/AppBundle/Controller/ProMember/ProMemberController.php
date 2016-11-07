<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Form\ProMemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProMemberController extends BaseController implements CrudInterface
{
    /**
     * Affichage de la liste
     * 
     * @Route("/prestataires", name="pro_member_index")
     */
    public function indexAction()
    {
        $users = $this->getRepository('AppBundle:ProMember')->findAll();

        return $this->render('pro_member/index.html.twig', ['users' => $users]);
    }

    /**
     * Nouveau enregistrement
     * @param Request $request
     */
    public function newAction(Request $request)
    {
    }

    /**
     * Affichage du profil
     * 
     * @Route("/prestataires/{slug}", name="user_profile")
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
     * Edition
     *
     */
    public function editAction()
    {
    }

    /**
     * @Route("/pro-member/update", name="pro_member_update")
     * @param Request $request
     * @return mixed|void
     */
    public function updateAction(Request $request)
    {
        /** @var ProMember $user */
        $user = $this->getUser();
        $form = $this->createForm(ProMemberEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
//            $password = $this->get('security.password_encoder')
//                ->encodePassword($user, $request->get('plainPassword'));
//            $user->setPassword($password);

            $this->createAvatarImage($form, $user);

            $this->em()->persist($user);
            $this->em()->flush();
        }

        return $this->redirect('/profil');
    }

    /**
     * Suppression
     *
     * @Route("/destroy", name="user_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function destroyAction(Request $request)
    {
        $user = $this->getUser();

        //suppression de l'utilisateur
        $this->deleteUser($request, $user);

        return $this->redirectToRoute('homepage');
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
        $message = \Swift_Message::newInstance()
            ->setSubject('Recommendation de prestataire')
            ->setFrom('noreply@bien-etre.com')
            ->setTo($request->get('mail'))
            ->setBody(
                $this->render('emails/commend-pro-user.html.twig', [
                    'message' => $request->get('message'),
                    'user'    => $proMember,
                ]),
                'text/html'
            );

        $this->get('mailer')->send($message);

        return $this->redirectToRoute('user_profile', [
            'slug' => $proMember->getSlug(),
        ]);
    }
}
