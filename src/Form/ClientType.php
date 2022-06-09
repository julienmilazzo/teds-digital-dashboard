<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\{AbstractType, Extension\Core\Type\CheckboxType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom* :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false,
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code Postal :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false,
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif : ',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-1 mb-4',
                ],
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
