<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Comment;
use AppBundle\Entity\CommentReport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @param  ReportComment $report
     * @return Response
     */
    public function dismissAction(CommentReport $report)
    {
        // $this->adminCheck();
        $this->em()->remove($report);
        $this->em()->flush();

        return $this->redirectToRoute('admin_comments');
    }

    /**
     * Supprimer le commentaire
     *
     * @Route("/admin/comments/{id}/destroy", name="admin_comments_destroy")
     * @param  Comment $comment
     * @return Response
     */
    public function destroyAction(Comment $comment)
    {
        // $this->adminCheck();

        $this->em()->remove($comment);
        $this->em()->flush();

        return $this->redirectToRoute('admin_comments');
    }
}