<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gestion des catégories
 */
class CategoryController extends BaseController
{
    /**
     * Affichage de toues les catégories
     *
     * @Route("/admin/categories", name="admin_categories")
     * @return Response
     */
    public function indexAction()
    {
        $categories = $this->getRepository('AppBundle:Category')->findAll();

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function newAction()
    {
        $form = $this->createForm(CategoryType::class);

        return $this->render('admin/categories/partials/_new-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Enregistrement du service
     *
     * @Route("/admin/categories/create", name="admin_categories_create")
     * @param  Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->em()->persist($category);
            $this->em()->flush();
        }

        return $this->redirectToRoute('admin_categories');
    }
}
