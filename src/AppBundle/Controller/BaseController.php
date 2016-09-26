<?php


namespace AppBundle\Controller;


use Doctrine\ORM\Mapping\Entity;
use Illuminate\Support\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getRepository($repo)
    {
        return $this->getDoctrine()->getRepository($repo);
    }

    protected function collection($array)
    {
        return new Collection($array);
    }
}
