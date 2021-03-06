<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Form\MemberType;
use AppBundle\Form\ProMemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    /**
     * Affiche la page du profil d'utilisateur selon son type
     *
     * @Route("/profil", name="edit_profile")
     * @var User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $this->userCheck();

        $user = $this->getUser();

        if ($user instanceof Member) {
            $form = $this->createForm(MemberType::class, $this->getUser());
            $template = 'member/edit.html.twig';
        }

        if ($user instanceof ProMember) {
            $form = $this->createForm(ProMemberType::class, $this->getUser());
            $template = 'pro_member/edit.html.twig';
        }

        return $this->render($template, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Confirmer un compte d'utilisateur
     *
     * @Route("/confirm/{token}", name="user_confirmation")
     * @param string $token
     * @return Response
     * @internal param User $user
     */
    public function confirmAction(string $token)
    {
        $user = $this->getRepository('AppBundle:User')->findBy(['confirmationToken' => $token]);
        $confirmed = $this->get('app.registration')->confirmUser($user, $token);

        if (!$confirmed) {
            $this->addFlash('confirmation', 'Erreur! Compte introuvable');

            return $this->redirectToRoute('login');
        }

        $this->addFlash('confirmation', 'Votre compte a été bien confirmé. Vous pouvez maintenant vous identifier.');

        $this->em()->persist($user);
        $this->em()->flush();

        return $this->redirectToRoute('login');
    }
}
