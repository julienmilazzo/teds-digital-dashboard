<?php

namespace App\Form;

use App\Entity\{ClickAndCollect, Client, DomainName, Server, Site};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, TextType};
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends ServiceType
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'hébergement :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('site', EntityType::class, [
                'label' => 'Site :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6',
                ],
                'class' => Site::class,
                'choice_label' => 'name',
                'mapped' => false,
                'multiple' => true,
                'required' => false
            ])
            ->add('domainName', EntityType::class, [
                'label' => 'Nom de domaine :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6',
                ],
                'class' => DomainName::class,
                'choice_label' => 'url',
                'mapped' => false,
                'multiple' => true,
                'required' => false
            ])
            ->add('clickAndCollect', EntityType::class, [
                'label' => 'Click & Collect :',
                'label_attr' => [
                    'class' => 'col-3 mb-4 label-form'
                ],
                'attr' => [
                    'class' => 'col-6',
                ],
                'class' => ClickAndCollect::class,
                'choice_label' => 'id',
                'mapped' => false,
                'multiple' => true,
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
                'required' => true
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function($event) {
            /** @var Form $form */
            $form = $event->getForm();
            /** @var Server $data */
            $data = $form->getData();
            $sites = $data->getSites();
            $form->get('site')->setData($sites);
            $form->get('domainName')->setData($this->em->getRepository(DomainName::class)->findBy(['server' => $data]));
            $form->get('clickAndCollect')->setData($data->getClickAndCollects());
        });
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}
