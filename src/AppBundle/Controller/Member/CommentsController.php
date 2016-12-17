<?php
namespace AppBundle\Controller\Member;


use AppBundle\Controller\BaseController;
use AppBundle\Entity\Comment;
use AppBundle\Entity\CommentReport;
use AppBundle\Entity\ProMember;
use AppBundle\Form\Comment\CommentReportType;
use AppBundle\Form\Comment\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends BaseController
{
    /**
     * @Route("/prestataires/{user}/comments/new", name="comments_new")
     * @param Request   $request
     * @param ProMember $user
     *
     * @return Response
     * @internal param ProMember $proMember
     *
     */
    public function newAction(Request $request, ProMember $user)
    {
        //Construction du formulaire de commentaire
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isValid()) {
            $comment->setMember($this->getUser());
            $comment->setProMember($user);
            $this->em()->persist($comment);
            $this->em()->flush();

            return JsonResponse::create($request->get('comment'), 200);
        }

        return $this->render(':pro_member/partials:_comments-form.html.twig', [
            'commentForm' => $commentForm->createView(),
            'proMember'   => $user,
        ]);
    }

    /**
     * Enregistre un commentaire signalé
     *
     * @Route("/comments/{id}/report", name="comments_report")
     * @param Request $request
     * @param Comment $id
     *
     * @return Response
     * @internal param Comment $comment
     *
     */
    public function reportAction(Request $request, Comment $id)
    {
        $this->userCheck();

        $report = new CommentReport();
        $form = $this->createForm(CommentReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $report->setDate(new \DateTime('now'));
            $report->setComment($id);
            $report->setMember($this->getUser());

            //Envoi de message
            $message = $this->sendEmail($this->getParameter('admin_mail'), 'system@bien-etre.com', $request->get('description'))
                ->setBody(
                    $this->render('emails/comment-report.html.twig', [
                        'user'    => $this->getUser()->getUsername(),
                        'url'     => $request->server->get('HTTP_REFERER'),
                        'message' => $request->request->get('comment_report')['description'],
                    ])
                );
            $this->get('mailer')->send($message);

            //Enregistrement dans la db
            $this->em()->persist($report);
            $this->em()->flush();

            return JsonResponse::create('ok', 200);
        }

        return $this->render('comments/report.html.twig', [
            'form'    => $form->createView(),
            'comment' => $id,
        ]);
    }
}
