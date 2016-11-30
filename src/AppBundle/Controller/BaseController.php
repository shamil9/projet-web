<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use Illuminate\Support\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 *
 * @package AppBundle\Controller
 */
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
    protected function createAvatarImage($user)
    {
        //Gestion d'avatar
        $folder = $this->getParameter('assets_root') . '/img/uploads/avatars/';
        /** @var UploadedFile $file */
        $file = $user->getPicture();
        $fileName = $user->getUsername() . '.' . $file->guessExtension();

        $file->move($folder, $fileName);

        $image = $this->get('app.image_manager')->make($folder . $fileName);
        $image->createAvatar();
        $user->setPicture($fileName);
    }

    protected function createSliderImage(Image $slide)
    {
        /** @var UploadedFile $file */
        $file = $slide->getPath();
        $fileName = random_int(0, 99999) . '.' . $file->guessExtension();
        $folder = $this->getParameter('assets_root') . '/img/uploads/slider/';

        $file->move($folder, $fileName);

        $slide->setPath($fileName);

        $image = $this->get('app.image_manager')->make($folder . $fileName);
        $image->createSlide();
    }

    protected function createCategoryImage(Category $category)
    {
        /** @var UploadedFile $file */
        $file = $category->getImage();
        $image = new Image();
        $fileName = random_int(0, 99999) . '.' . $file->guessExtension();
        $folder = $this->getParameter('assets_root') . '/img/uploads/category/';

        $file->move($folder, $fileName);

        $image->setPath($fileName);
        $image->setType('category');
        $category->setImage($image);

        $imageManager = $this->get('app.image_manager')->make($folder . $fileName);
        $imageManager->createCategoryImage();
    }

    protected function userCheck()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
    }

    protected function adminCheck()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    }

    protected function proUserCheck()
    {
        $this->denyAccessUnlessGranted('ROLE_PRO_USER');
    }

    /**
     * Dump and die pour debug
     * @param $content
     */
    protected function dd($content)
    {
        dump($content);
        die();
    }

    protected function sendEmail($to, $from, $subject)
    {
        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to);
    }
}
