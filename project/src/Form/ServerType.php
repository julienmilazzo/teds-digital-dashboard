<?php

namespace App\Form;

use App\Entity\Server;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('provider', TextType::class, [
                'label' => 'Prestataire :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('offer', TextType::class, [
                'label' => 'Offre :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('invoicedPrice', NumberType::class, [
                'label' => 'Prix payé :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('renewalType', TextType::class, [
                'label' => 'Type de renouvellement :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('renawalDate', DateType::class, [
                'label' => 'Date de renouvellement :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('site', EntityType::class, [
                'label' => 'Site :',
                'label_attr' => [
                    'class' => 'col-3 mb-4'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Site::class,
                'choice_label' => 'name',
                'mapped' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}
