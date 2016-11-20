<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BaseController
{
    /**
     * Admin Index
     *
     * @Route("/admin", name="admin_index")
     */
    public function indexAction()
    {
        // $this->adminCheck();
        $proMembers = $this->getRepository('AppBundle:ProMember')->findAll();
        $members = $this->getRepository('AppBundle:Member')->findAll();
        $workshops = $this->getRepository('AppBundle:Workshop')->findAll();
        $sales = $this->getRepository('AppBundle:Sale')->findAll();

        return $this->render('admin/index.html.twig', compact('proMembers', 'members', 'workshops', 'sales'));
    }
}