<?php


namespace AppBundle\Form\Member;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberEditType extends AbstractType
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
//            ->add('currentPassword', ChangePassword::class, [
//                'required' => false,
//                'mapped' => false,
//            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'Mots de passe ne corresponds pas',
                'first_options'   => ['label' => 'Mot de passe'],
                'second_options'  => ['label' => 'Répétez le mot de passe'],
                'required'        => false,
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required'   => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'AppBundle\Entity\Member',
            'validation_groups' => ['member_edit'],
        ]);
    }
}
