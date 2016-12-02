<?php


namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Image;
use AppBundle\Entity\ProMember;
use AppBundle\Form\SliderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SliderController extends BaseController
{

    /**
     * Affichage de la liste complète
     */
    public function indexAction()
    {
        /** @var Image $images */
        $images = $this->getRepository('AppBundle:Image')->findBy(['user' => $this->getUser()], ['id' => 'DESC']);

        return $this->render(':pro_member/partials:_slider-images-list.html.twig', [
            'images' => $images,
        ]);
    }

    /**
     * Nouveau enregistrement
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $this->userCheck();

        $form = $this->createForm(SliderType::class);

        return $this->render('slider/_partials/_new-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Enregistrer image slider
     *
     * @Route("/slider/create", name="slider_create")
     * @param  Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $this->userCheck();

        $sliderImage = new Image();
        $form = $this->createForm(SliderType::class, $sliderImage);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $image = $this->get('app.image_storage_manager')->storeSliderImage($sliderImage);
            $imageManager = $this->get('app.image_manager')->make($image);
            $imageManager->createSlide();

            $sliderImage->setUser($this->getUser());
            $sliderImage->setType('user-slider');
            $sliderImage->setPath($imageManager->image->basename);

            $this->em()->persist($sliderImage);
            $this->em()->flush();

            return JsonResponse::create(null, 200);
        }
        return JsonResponse::create(null, 500);
    }

    /**
     * Suppression
     * @Route("/slider/{slider}/destroy", name="slider_destroy")
     * @param Request $request
     * @return SliderController|JsonResponse|Response
     */
    public function destroyAction(Request $request)
    {
        try {
            /** @var Image $image */
            $image = $this->getRepository('AppBundle:Image')->findOneBy(['id' => $request->get('slider')]);

            if ($this->getUser() != $image->getUser()) {
                $this->createAccessDeniedException('Action non autorisée');
            }

            //Suppression du fichier
            unlink($this->getParameter('sliders_folder') . $image->getPath());

            $this->em()->remove($image);
            $this->em()->flush();

            return JsonResponse::create();
        } catch (Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }
}
