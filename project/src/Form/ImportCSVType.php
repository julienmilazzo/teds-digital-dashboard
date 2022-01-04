<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ImportCSVType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choiceEntity', ChoiceType::class, [
                'label' => 'Ajouter csv pour :',
                'label_attr' => [
                    'class' => 'col-3'
                ],
                'attr' => [
                    'class' => 'col-7',
                ],
                'choices' => [
                    'Client' => 'client',
                    'Nom de domain' => 'nomDeDomain',
                    'Site' => 'site',
                    'Serveur' => 'serveur',
                ],
                'mapped' => false,
            ])
            ->add('csv', FileType::class, [
                'label' => 'CSV à ajouter :',
                'label_attr' => [
                    'class' => 'col-3'
                ],
                'attr' => [
                    'class' => 'col-7',
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'mt-4 col-3 btn btn-light'
                ],
            ]);
    }
}
