<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
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

        return $this->render('admin/categories/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Enregistrement du service
     *
     * @Route("/admin/categories/create", name="admin_categories_create")
     * @param  Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->removePreviousPromotion();
            $image = new Image();

            $categoryImage = $this->get('app.image_storage_manager')->storeCategoryImage($category);
            $imageManager = $this->get('app.image_manager')->make($categoryImage);
            $imageManager->createCategoryImage();

            $image->setPath($imageManager->image->basename);
            $image->setType('category');
            $category->setImage($image);

            $this->em()->persist($category);
            $this->em()->flush();

            $this->log($category->getName() . ' category created.');
        }

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * Mise à jour de la catégorie
     *
     * @Route("/admin/category/{id}", name="admin_categories_update")
     * @param Request  $request
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Category $category)
    {
        $currentImage = $category->getImage();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            if (!is_null($request->files->get('category')['image'])) {
                //Suppression de l'encienne image
                unlink($this->getParameter('categories_folder') . $currentImage->getPath());
                $this->em()->remove($currentImage);

                $this->createCategoryImage($category);
            }

            if ($category->getPromoted()) {
                $this->removePreviousPromotion();
            }

            $this->em()->persist($category);
            $this->em()->flush();

            $this->log($category->getName() . ' category updated.');

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/update.html.twig', [
            'form'     => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * Supression de la catégorie
     *
     * @Route("/admin/category/{id}/destroy", name="admin_categories_destroy")
     * @param Request   $request
     * @param  Category $category
     * @return Response
     */
    public function destroyAction(Request $request, Category $category)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_category_destroy_token', $token)) {
            $this->em()->remove($category);
            $this->em()->flush();
        }

        $this->log($category->getName() . ' category removed.');

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * Supprimer la promotion de la précédente catégorie
     */
    private function removePreviousPromotion()
    {
        $promoted = $this->collection(
            $this->getRepository('AppBundle:Category')->findBy(['promoted' => true])
        );

        $promoted->each(function ($featuredCat) {
            $featuredCat->setPromoted(0);
            $this->log($featuredCat->getName() . ' removed category promotion.');
            $this->em()->persist($featuredCat);
        });

        $this->em()->flush();
    }

    /**
     * Approuve une catégorie suggérée par un prestataire
     *
     * @Route("/admin/category/{id}/approve", name="admin_categories_approve")
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approuveCategoryAction(Category $category)
    {
        $category->setIsActive(1);

        $this->em()->persist($category);
        $this->em()->flush();

        $this->log($category->getName() . ' category approved.');

        return $this->redirectToRoute('admin_categories');
    }
}
