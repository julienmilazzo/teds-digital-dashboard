<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('onlineDate')
            ->add('online')
            ->add('server')
        ;


        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var Site $site */
            $site = $event->getData();
            $clientAvailable = $this->em->getRepository(Client::class)->findAll();
//            dd($clientAvailable);
            $event->getForm()->add('client', EntityType::class, [
                'label' => 'Client: ',
                'label_attr' => [
                    'class' => 'col-3 mb-4'
                ],
                'attr' => [
                    'class' => 'col-6'
                ],
                'mapped' => false,
                'required' => true,
                'class' => Client::class,
                'choices' => $clientAvailable,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
