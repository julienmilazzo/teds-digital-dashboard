<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\DomainName;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, DateType, NumberType, TextType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainNameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'Url :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('provider', TextType::class, [
                'label' => 'Prestataire :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'required' => false
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
                'required' => false
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
                'required' => false
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
            ->add('site', EntityType::class, [
                'label' => 'Site :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Site::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => false
            ])
            ->add('client', EntityType::class, [
                'label' => 'Client :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Client::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => true
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
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DomainName::class,
        ]);
    }
}
