<?php


namespace AppBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;

interface FormInterface
{
    public function process();

    public function save(ObjectManager $em);
}
