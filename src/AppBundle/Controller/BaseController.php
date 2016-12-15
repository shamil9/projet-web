<?php


namespace AppBundle\Controller;

use Illuminate\Support\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em()
    {
        return $this->getDoctrine()->getManager();
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

    protected function log($message)
    {
        $logger = $this->get('logger');
        $logger->info($message);
    }
}
