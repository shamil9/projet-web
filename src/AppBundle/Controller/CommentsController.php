<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use AppBundle\Entity\ProMember;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends BaseController
{
    /**
     * @Route("/prestataires/{slug}/commentaires", name="pro_member_comments_new")
     * @param Request $request
     * @param ProMember $proMember
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, ProMember $proMember)
    {
        //Construction du formulaire de commentaire
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setMember($this->getUser());
            $comment->setProMember($proMember);
            $this->em()->persist($comment);
            $this->em()->flush();

            return $this->redirectToRoute('pro_member_profile', array(
                'slug' => $proMember->getSlug()
            ));
        }

        return $this->render(':pro_member/partials:_comments-form.html.twig', [
            'commentForm' => $commentForm->createView(),
            'proMember' => $proMember,
        ]);
    }

    public function createAction(Request $request, Form $form)
    {
    }
}
