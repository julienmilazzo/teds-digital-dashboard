<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, DateType, NumberType, TextareaType, TextType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('provider', TextType::class, [
                'label' => 'Prestataire :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('offer', TextType::class, [
                'label' => 'Offre :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('cost', NumberType::class, [
                'label' => 'Coût :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('invoicedPrice', NumberType::class, [
                'label' => 'Prix facturé :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('renewalType', TextType::class, [
                'label' => 'Type de renouvellement :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('renewalDate', DateType::class, [
                'label' => 'Date de renouvellement :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                    'style' => 'display: inline'
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                    'style' => 'display: inline'
                ],
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
            ->add('commentary', TextareaType::class, [
                'label' => 'Commentaire :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                    'style' => 'height: 115px',
                    'placeholder' => 'Ajouter un commentaire ici...'
                ],
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
            'data_class' => Service::class,
        ]);
    }
}
