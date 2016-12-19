<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Image;
use AppBundle\Form\SliderType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* Gestion des images du slider sur la page d'accueil
*/
class SliderController extends BaseController
{
    /**
     * Affichage de toutes les images du slider
     *
     * @Route("/admin/sliders", name="admin_sliders")
     * @return Response
     */
    public function indexAction()
    {
        $sliders = $this->getRepository('AppBundle:Image')->findBy(['type' => 'admin-slider']);

        return $this->render('admin/sliders/index.html.twig', ['sliders' => $sliders]);
    }

    /**
     * Construction de formulaire d'ajout
     *
     * @return Response
     */
    public function newAction()
    {
        $form = $this->createForm(SliderType::class);

        return $this->render('admin/sliders/partials/_form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Enregistrement de l'image de slider
     *
     * @Route("/admin/sliders/create", name="admin_sliders_create")
     * @param  Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $sliderImage = new Image();
        $form = $this->createForm(SliderType::class, $sliderImage);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $image = $this->get('app.image_storage_manager')->storeSliderImage($sliderImage);
            $imageManager = $this->get('app.image_manager')->make($image);
            $imageManager->createSlide();

            $sliderImage->setType('admin-slider');
            $sliderImage->setPath($imageManager->image->basename);

            $this->em()->persist($sliderImage);
            $this->em()->flush();

            $this->log('Slider image added');

            return JsonResponse::create(null, 200);
        }

        return JsonResponse::create(null, 500);
    }

    /**
     * Supprime l'image du slider
     *
     * @Route("/admin/sliders/{id}/destroy", name="admin_sliders_destroy")
     * @param Request $request
     * @param  Image  $slide
     * @return JsonResponse
     */
    public function destroyAction(Request $request, Image $slide)
    {
        $token = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('admin_slider_destroy_token', $token)) {
            $this->em()->remove($slide);
            $this->em()->flush();

            //Supression de l'image
            unlink($this->getParameter('assets_root') . '/img/uploads/slider/' . $slide->getPath());
            $this->log('Slider image deleted');
        }

        return $this->redirectToRoute('admin_sliders');
    }
}