<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
* Ajoute le champs Image pour les catégories
*/
class AddImageFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $category = $event->getData();
        $form = $event->getForm();

        if (!$category || null === $category->getId()) {
            $form->add('image', FileType::class, [
                'data_class' => null,
                'required' => true
            ]);
        } else {
            $form->add('image', FileType::class, [
                'data_class' => null,
                'required' => false
            ]);
        }
    }
}