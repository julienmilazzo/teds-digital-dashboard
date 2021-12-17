<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Server;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du site :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                ],
            ])
            ->add('onlineDate', DateType::class, [
                'label' => 'Date de mise en ligne :',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-7 mb-4',
                    'style' => 'display: inline'
                ],
            ])
            ->add('online', CheckboxType::class, [
                'label' => 'En ligne : ',
                'label_attr' => [
                    'class' => 'col-3 mb-4',
                    'style' => 'vertical-align: top;'
                ],
                'attr' => [
                    'class' => 'col-1 mb-4',
                ],
            ])
            ->add('client', EntityType::class, [
                'label' => 'Client :',
                'label_attr' => [
                    'class' => 'col-3 mb-4'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Client::class,
                'choice_label' => 'name',
            ])
            ->add('server', EntityType::class, [
                'label' => 'Serveur :',
                'label_attr' => [
                    'class' => 'col-3 mb-4'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Server::class,
                'choice_label' => 'provider',
                'mapped' => false,
                'multiple' => true
            ])
        ;

//        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
//            /** @var Site $site */
//            $site = $event->getData();
//            $shop = $product->getShop();
//            $cientAvailables = $this->em->getRepository(Category::class)
//                ->findBy([
//                    'shop' => $product->getId() ? $product->getShop() : $shop
//                ])
//            ;
//            $event->getForm()->add('categoryList', EntityType::class, [
//                'label' => 'Catégorie *: ',
//                'label_attr' => [
//                    'class' => 'col-3 mb-4'
//                ],
//                'attr' => [
//                    'class' => 'col-6'
//                ],
//                'mapped' => false,
//                'required' => true,
//                'class' => Category::class,
//                'choices' => $categoriesAvailable,
//                'choice_label' => 'name',
//            ]);
//        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
