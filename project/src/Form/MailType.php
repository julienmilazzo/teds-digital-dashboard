<?php

namespace App\Form;

use App\Entity\{Client, DomainName, Mail};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailType extends ServiceType
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
            ->add('domainName', EntityType::class, [
                'label' => 'Nom de domaine :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => DomainName::class,
                'choice_label' => 'url',
                'required' => true
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
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mail::class,
        ]);
    }
}
