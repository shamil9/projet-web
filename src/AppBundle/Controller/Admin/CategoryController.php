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
        // $this->adminCheck();

        $categories = $this->getRepository('AppBundle:Category')->findAll();

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function newAction()
    {
        // $this->adminCheck();

        $form = $this->createForm(CategoryType::class);

        return $this->render('admin/categories/new.html.twig', [
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
        // $this->adminCheck();

        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->createCategoryImage($category);

            $this->em()->persist($category);
            $this->em()->flush();
        }

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * Mise à jour de la catégorie
     *
     * @Route("/admin/category/{id}", name="admin_categories_update")
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Category $category)
    {
        // $this->adminCheck();

        $currentImage = $category->getImage();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
           if (!is_null($request->files->get('category')['image'])) {
                //Supression de l'encienne image
                unlink($this->getParameter('assets_root') . '/img/uploads/category/' . $currentImage->getPath());
                $this->em()->remove($currentImage);

                $this->createCategoryImage($category);
           }
            $this->em()->persist($category);
            $this->em()->flush();

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/update.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * Supression de la catégorie
     *
     * @Route("/admin/category/{id}/destroy", name="admin_categories_destroy")
     * @param  Category $category
     * @return Response
     */
    public function destroyAction(Category $category)
    {
        // $this->adminCheck();
        $this->em()->remove($category);
        $this->em()->flush();

        return $this->redirectToRoute('admin_categories');
    }
}
