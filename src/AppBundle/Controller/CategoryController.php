<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Event\EmailNotification;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    /**
     * La liste des tous les services
     *
     * @Route("/services", name="category_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
     * Suggestion de catégorie de service
     *
     * @Route("categorie/ajouter", name="category_new")
     */
    public function newAction()
    {
        $form = $this->createForm(CategoryType::class);

        return $this->render('category/new.html.twig', [
            'catSubmitForm' => $form->createView(),
        ]);
    }

    /**
     * Enregistrement de la catégories proposée
     *
     * @Route("/category/submit", name="category_submit")
     * @param  Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $this->proUserCheck();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $category->setIsActive(0);

            $image = new Image();
            $categoryImage = $this->get('app.image_storage_manager')->storeCategoryImage($category);
            $imageManager = $this->get('app.image_manager')->make($categoryImage);
            $imageManager->createCategoryImage();

            // Enregistrement d'image
            $image->setPath($imageManager->image->basename);
            $image->setType('category');
            $category->setImage($image);

            $this->em()->persist($category);
            $this->em()->flush();

            // Envoi d'email de notification
            $event = new EmailNotification(['category' => $category, 'user' => $this->getUser()]);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('category.submission', $event);
        }

        return $this->redirectToRoute('pro_user_profile', ['slug' => $this->getUser()->getSlug()]);
    }
}
