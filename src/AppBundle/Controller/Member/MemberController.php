<?php


namespace AppBundle\Controller\Member;

use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends BaseController implements CrudInterface
{
    /**
     * Affichage de la liste complète
     */
    public function indexAction()
    {
    }

    /**
     * Nouveau enregistrement
     * @param Request $request
     */
    public function newAction(Request $request)
    {
    }

    /**
     * Affichage individuel
     * @param $entity
     */
    public function showAction($entity)
    {
    }

    /**
     * Edition
     */
    public function editAction()
    {
    }

    /**
     * Met à jour les données d'utilisateur
     *
     * @Route("/member/update", name="member_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $this->userCheck();

        /** @var Member $user */
        $form = $this->createForm(MemberEditType::class, $user = $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Changement de mot de passe
            if ($request->getPassword()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $request->get('password'));
                $user->setPassword($password);
                dump($user->getPassword());
                die();
            }

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

}
