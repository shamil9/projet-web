<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    /**
     * La liste des tous les services
     *
     * @Route("/services", name="category_list")
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getRepository('AppBundle:Category')->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Affichage de service et les prestataires qui le propose
     *
     * @Route("/services/{slug}", name="category_show")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Category $category)
    {
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * Suggestion de catégorie de sérvice
     *
     * @param  Request $request
     * @return Render
     */
    public function newAction()
    {
        $form = $this->createForm(CategoryType::class);

        $this->render('admin/categories/partials/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Enregistrement de la catégories proposée
     *
     * @Route("/category/submmit", name="category_submit")
     * @param  Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $this->proUserCheck();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $category->setIsActive(0);

            $this->em()->persist($category);
            $this->em()->flush();

            JsonResponse::create(null, 200);
        }

        JsonResponse::create(null, 500);
    }
}
