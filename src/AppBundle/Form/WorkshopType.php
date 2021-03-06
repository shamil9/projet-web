<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkshopType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class, [
                'invalid_message' => 'Ce format est invalide, entrez un nombre',
            ])
            ->add('start', DateTimeType::class, [
                'format' => 'dd/MM/y HH:mm',
                'widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'format' => 'dd/MM/y HH:mm',
                'widget' => 'single_text'
            ])
            ->add('displayFrom', DateTimeType::class, [
                'format' => 'dd/MM/y HH:mm',
                'widget' => 'single_text'
            ])
            ->add('displayUntil', DateTimeType::class, [
                'format' => 'dd/MM/y HH:mm',
                'widget' => 'single_text',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Workshop',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'workshop',
        ));
    }
}
