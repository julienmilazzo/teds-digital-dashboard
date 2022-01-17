<?php

namespace App\Form;

use App\Entity\{Ad, Client, Site, SocialNetwork};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ServiceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                    'style' => 'display: inline'
                ],
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
                'required' => true
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
                'required' => false
            ])
            ->add('socialNetwork', EntityType::class, [
                'label' => 'Réseau social :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => SocialNetwork::class,
                'choice_label' => 'id',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
