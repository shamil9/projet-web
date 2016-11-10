<?php


namespace AppBundle\Controller\ProMember;


use AppBundle\Controller\BaseController;
use AppBundle\Controller\CrudInterface;
use AppBundle\Entity\Image;
use AppBundle\Entity\ProMember;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SliderController extends BaseController implements CrudInterface
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
     * @Route("/slider/add", name="slider_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $this->userCheck();

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isValid() && $request->files->has('images')) {
            /** @var ProMember $user */
            $user = $this->getUser();
            $image = new Image();

            /** @var UploadedFile $file */
            $file = $request->files->get('images');
            $fileName = $user->getName() . '_' . random_int(0, 99999) . '.' . $file->guessExtension();
            $folder = $this->getParameter('assets_root') . '/img/uploads/slider/';

            $file->move($folder, $fileName);
            $this->createSliderImage($folder . $fileName);

            $image->setUser($user);
            $image->setPath($fileName);

            $this->em()->persist($image);
            $this->em()->flush();

            return Response::create();
        }

        return $this->render('pro_member/partials/_slider-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affichage individuel
     * @param $entity
     */
    public function showAction($entity)
    {
        // TODO: Implement showAction() method.
    }

    /**
     * Edition
     */
    public function editAction()
    {
        // TODO: Implement editAction() method.
    }

    /**
     * Mise à jour
     *
     * @param Request $request
     * @return mixed
     */
    public function updateAction(Request $request)
    {
        // TODO: Implement updateAction() method.
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
            
            unlink($this->getParameter('assets_root') . '/img/uploads/slider/' . $image->getPath());

            $this->em()->remove($image);
            $this->em()->flush();

            return JsonResponse::create();
        } catch (Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }
}
