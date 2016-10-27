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
     * @Route("/editer/{user}", name="member_edit")
     * @param Request $request
     * @param Member $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Member $user)
    {
        $form = $this->createForm(MemberEditType::class, $user);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $user->getAvatar();
            $fileName = $user->getUsername().'.'.$file->guessExtension();
            $folder = $this->getParameter('assets_root') . '/img/uploads/avatars/';

            $file->move( $folder, $fileName );

            $this->resizeImage($folder . $fileName);

            //pas sûr de ce code
            $image = new Image();
            $image->setMember($user);
            $image->setPath($folder . $fileName);

            $user->setAvatar($image);
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
        $image = $this->get('app.media_manager')->make($file);
        $image->resize(50, 50)->save($file);

    }
}
