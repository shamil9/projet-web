<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Comment;
use AppBundle\Entity\CommentReport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
* Gestion des commentaires
*/
class CommentController extends BaseController
{

    /**
     * Affichage des commentaires abusif
     *
     * @Route("/admin/comments", name="admin_comments")
     * @return Response
     */
    public function indexAction()
    {
        $reports = $this->getRepository('AppBundle:CommentReport')->findAll();

        return $this->render('admin/comments/index.html.twig', [
            'reports' => $reports,
        ]);
    }

    /**
     * Supprimer un signalement
     *
     * @Route("/admin/comments/report/{id}/destroy", name="admin_comments_report_dismiss")
     * @param Request        $request
     * @param  CommentReport $report
     * @return Response
     */
    public function dismissAction(Request $request, CommentReport $report)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_comment_dismiss_token', $token)) {
            $this->em()->remove($report);
            $this->em()->flush();
        }

        $this->log('Signalement du commentaire supprimé');

        return $this->redirectToRoute('admin_comments');
    }

    /**
     * Supprimer le commentaire
     *
     * @Route("/admin/comments/{id}/destroy", name="admin_comments_destroy")
     * @param Request  $request
     * @param  Comment $comment
     * @return Response
     */
    public function destroyAction(Request $request, Comment $comment)
    {
        // $this->adminCheck();
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_comment_destroy_token', $token)) {
            $this->em()->remove($comment);
            $this->em()->flush();
        }

        $this->log('Commentaire supprimé');

        return $this->redirectToRoute('admin_comments');
    }
}