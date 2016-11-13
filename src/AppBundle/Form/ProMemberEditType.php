<?php


namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProMemberEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
//            ->add('currentPassword', PasswordType::class, [
//                'required' => false,
//                'mapped' => false,
//                'constraints' => new ChangePassword()
//            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'           => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
                'required'       => false,
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required'   => false,
            ])
            ->add('categories', EntityType::class, [
                'class'        => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'multiple'     => true,
            ])
            ->add('description', TextareaType::class)
            ->add('phone', TextType::class)
            ->add('street', TextType::class)
            ->add('zip', IntegerType::class)
            ->add('city', TextType::class)
            ->add('tva', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'AppBundle\Entity\ProMember',
            'validation_groups' => ['pro_member_edit'],
        ]);
    }
}
