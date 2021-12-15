<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'usager' => User::ROLE_USAGER,
            'admin' => User::ROLE_ADMIN
        ];
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle :',
                'label_attr' => [
                    'class' => 'col-3',
                ],
                'attr' => [
                    'class' => 'col-9 d-inline-block'
                ],
                'choices' => $roles,
                'expanded' => true,
                'multiple' => true,
                'required' => true
            ])
            ->add('password', TextType::class, [
                'label' => 'Mot de passe :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
