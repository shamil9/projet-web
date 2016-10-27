<?php


namespace AppBundle\Controller\Member;


use AppBundle\Entity\Comment;
use AppBundle\Controller\BaseController;
use AppBundle\Entity\ProMember;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends BaseController
{
    /**
     * @Route("/prestataires/{slug}/commentaires/nouveau", name="pro_member_comments_new")
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

        if ($commentForm->isValid()) {
            $comment->setMember($this->getUser());
            $comment->setProMember($proMember);
            $this->em()->persist($comment);
            $this->em()->flush();

            return JsonResponse::create($request->get('comment'), 200);
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
