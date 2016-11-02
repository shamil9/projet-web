<?php


namespace AppBundle\Controller\Member;


use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends BaseController implements CrudInterface
{
    /**
     * Affichage de la liste complète
     */
    public function indexAction()
    {
        // TODO: Implement indexAction() method.
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
     * Affichage individuel
     * @param $entity
     */
    public function showAction( $entity )
    {
        // TODO: Implement showAction() method.
    }

    /**
     * Edition
     * @Route("/profil", name="edit_profile")
     */
    public function editAction()
    {
        /** @var Member $user */
        $user = $this->getUser();

        $form = $this->createForm( MemberEditType::class );

        return $this->render( 'pro_member/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ] );
    }

    /**
     * Met à jour les données d'utilisateur
     *
     * @Route("/update", name="member_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $form = $this->createForm(MemberEditType::class, $user = $this->getUser());
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            //Changement de mot de passe
            $password = $this->get('security.password_encoder')
                             ->encodePassword($user, $request->get('plainPassword'));
            $user->setPassword($password);

            //Gestion d'avatar
            if ( $request->files->has( 'picture' ) ) {
                /** @var UploadedFile $file */
                $file = $user->getPicture();
                $fileName = $user->getUsername().'.'.$file->guessExtension();
                $folder = $this->getParameter('assets_root') . '/img/uploads/avatars/';

                $file->move( $folder, $fileName );

                $this->createAvatarImage( $folder . $fileName );
                $user->setPicture($fileName);
            }

            $this->em()->persist($user);
            $this->em()->flush();

        }

        return $this->redirectToRoute('user_profile');
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

}
