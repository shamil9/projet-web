<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    /**
     * @Route("/services", name="service_list")
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getRepository('AppBundle:Category')->findAll();

        return $this->render('services/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/services/{slug}", name="service_show")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Category $category)
    {
        return $this->render('services/show.html.twig', [
            'category' => $category
        ]);
    }
}
