<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\Sale;
use AppBundle\Entity\User;
use AppBundle\Form\ProMemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProMemberController extends BaseController implements CrudInterface
{
    /**
     * @Route("/prestataires", name="pro_member_index")
     */
    public function indexAction()
    {
        $users = $this->getRepository( 'AppBundle:ProMember' )->findAll();

        return $this->render( 'pro_member/index.html.twig', [ 'users' => $users ] );
    }

    /**
     * Nouveau enregistrement
     * @param Request $request
     */
    public function newAction( Request $request )
    {
        // TODO: Implement newAction() method.
    }

    /**
     * @Route("/prestataires/{slug}", name="user_profile")
     * @ParamConverter("user", class="AppBundle:ProMember")
     * @param ProMember $user
     * @return string
     * @internal param Request $request
     * @internal param ProMember $proMember
     * @internal param ProMember|User $user
     */
    public function showAction( $user )
    {
        if ($this->getUser() instanceof Member) {
            $repo = $this->em()->getRepository('AppBundle:Favorite');

            //Recherche de prestataire dans le favoris
            $favorite = $repo->findOneBy([
                'proMember' => $user->getId(),
                'member' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('pro_member/show.html.twig', [
            'user' => $user,
            'favorite' => $favorite ?? null,
        ]);
    }

    /**
     * Edition
     *
     * @Route("/profil", name="edit_profile")
     */
    public function editAction()
    {
        /** @var ProMember $user */
        $user = $this->getUser();

        $form = $this->createForm( ProMemberEditType::class );

        return $this->render( 'pro_member/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ] );
    }

    /**
     * @Route("/update", name="update_profile")
     * @param Request $request
     * @return mixed|void
     */
    public function updateAction( Request $request )
    {
        $form = $this->createForm( ProMemberEditType::class, $user = $this->getUser() );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {

            //Changement de mot de passe
            $password = $this->get( 'security.password_encoder' )
                ->encodePassword( $user, $request->get( 'plainPassword' ) );
            $user->setPassword( $password );

            //Gestion d'avatar
            if ( $request->get( 'picture' ) ) {
                /** @var UploadedFile $file */
                $file     = $user->getPicture();
                $fileName = $user->getUsername() . '.' . $file->guessExtension();
                $folder   = $this->getParameter( 'assets_root' ) . '/img/uploads/avatars/';

                $file->move( $folder, $fileName );

                $this->createAvatarImage( $folder . $fileName );
                $user->setPicture( $fileName );
            }

            $this->em()->persist( $user );
            $this->em()->flush();

        }

        return $this->redirectToRoute( 'user_profile' );
    }

    /**
     * Suppression
     *
     * @Route("/destroy", name="user_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function destroyAction( Request $request )
    {
        $user = $this->getUser();

        //suppression de l'utilisateur
        $this->deleteUser( $request, $user );

        return $this->redirectToRoute( 'homepage' );
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

        return $this->redirectToRoute( 'user_profile', [
            'slug' => $proMember->getSlug()
        ]);
    }
}
