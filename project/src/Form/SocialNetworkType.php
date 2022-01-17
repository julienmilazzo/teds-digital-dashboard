<?php

namespace App\Form;

use App\Entity\{Client, SocialNetwork};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialNetworkType extends ServiceType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('postByWeek', NumberType::class, [
                'label' => 'Nombre de post par semaine :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('whichSocialNetworks', ChoiceType::class, [
                'label' => 'Quels réseaux sociaux :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
                'choices' => [
                    'Facebook' => SocialNetwork::FACEBOOK,
                    'Instagram' => SocialNetwork::INSTAGRAM,
                    'Linkedin' => SocialNetwork::LINKEDIN,
                ],
                'mapped' => false,
                'multiple' => true
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
        ;
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialNetwork::class,
        ]);
    }
}
