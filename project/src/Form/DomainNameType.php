<?php

namespace App\Form;

use App\Entity\DomainName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url')
            ->add('provider')
            ->add('offer')
            ->add('price')
            ->add('invoicedPrice')
            ->add('renewalType')
            ->add('renawalDate')
            ->add('server')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DomainName::class,
        ]);
    }
}
