<?php


namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Illuminate\Support\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    /**
     * @param $repo
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($repo)
    {
        return $this->getDoctrine()->getRepository($repo);
    }

    /**
     * Retourne une instance de Laravel Collection
     *
     * @param $array
     * @return Collection
     */
    protected function collection($array)
    {
        return new Collection($array);
    }

    /**
     * @param Request $request
     * @param $user
     */
    protected function deleteUser(Request $request, User $user)
    {
        //suppression d'utilistateur
        $this->em()->remove($user);
        $this->em()->flush();

        //suppression de la session
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $form Form
     * @param $user User
     */
    protected function createAvatarImage($form, $user)
    {
        //Gestion d'avatar
        if (!$form['picture']->isEmpty()) {
            $folder = $this->getParameter('assets_root') . '/img/uploads/avatars/';
            /** @var UploadedFile $file */
            $file = $user->getPicture();
            $fileName = $user->getUsername() . '.' . $file->guessExtension();

            $file->move($folder, $fileName);

            $image = $this->get('app.image_manager')->make($folder . $fileName);
            $image->createAvatar();
            $user->setPicture($fileName);
        }
    }

    /**
     * @param string $file
     */
    protected function createSliderImage(string $file)
    {
        $image = $this->get('app.image_manager')->make($file);
        $image->createSlide();
    }

    protected function userCheck()
    {
        if (!$this->getUser()) {
            $this->createAccessDeniedException('Action non autorisée');
        }
    }
}
