<?php


namespace AppBundle\Controller\Member;


use AppBundle\Controller\BaseController;
use AppBundle\Entity\Image;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends BaseController
{
    /**
     * Met à jour les données d'utilisateur
     *
     * @Route("/editer", name="member_edit")
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
            if ( $request->get('picture') ) {
                /** @var UploadedFile $file */
                $file = $user->getPicture();
                $fileName = $user->getUsername().'.'.$file->guessExtension();
                $folder = $this->getParameter('assets_root') . '/img/uploads/avatars/';

                $file->move( $folder, $fileName );

                $this->resizeImage($folder . $fileName);
                $user->setPicture($fileName);
            }

            $this->em()->persist($user);
            $this->em()->flush();

        }

        return $this->redirectToRoute('user_profile');
    }

    /**
     * @param string $file
     */
    private function resizeImage( string $file )
    {
        $image = $this->get('app.image_manager')->make($file);
        $image->createAvatar();
    }
}
