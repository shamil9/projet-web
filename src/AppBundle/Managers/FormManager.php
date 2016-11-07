<?php


namespace AppBundle\Managers;

use AppBundle\Form\FormInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FormManager
{
    /** @var FormInterface $form */
    private $form;

    /**
     * @param FormInterface $form
     */
    public function process(FormInterface $form)
    {
        $form->process();

        $this->form = $form;
    }

    public function save(ObjectManager $em)
    {
        $this->form->save($em);
    }
}
