<?php

namespace App\Form;

use App\Entity\SocialNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postByWeek')
            ->add('whichSocialNetwork')
            ->add('provider')
            ->add('offer')
            ->add('cost')
            ->add('invoicedPrice')
            ->add('renewalType')
            ->add('renewalDate')
            ->add('startDate')
            ->add('commentary')
            ->add('enable')
            ->add('siteClientToServicesBinderId')
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialNetwork::class,
        ]);
    }
}
