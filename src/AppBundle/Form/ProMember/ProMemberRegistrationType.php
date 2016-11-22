<?php


namespace AppBundle\Form\ProMember;

use Faker\Provider\DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProMemberRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe']
            ])
            ->add('name', TextType::class)
            ->add('city', ChoiceType::class)
            ->add('street', TextType::class)
            ->add('zip', NumberType::class)
            ->add('phone', TextType::class)
            ->add('website', UrlType::class)
            ->add('tva', TextType::class)
            ->add('categories', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('description', TextareaType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $city = $event->getData()['city'];
            $form = $event->getForm();

            $form->remove('city');

            $form->add('city', TextType::class, [
                'data' => $city,
            ]);
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ProMember'
        ]);
    }
}
