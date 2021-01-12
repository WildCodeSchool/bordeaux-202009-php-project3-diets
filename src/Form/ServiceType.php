<?php

namespace App\Form;

use App\Entity\Picture;
use App\Entity\Service;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Titre'])
            ->add('link', TextType::class, ['label' => 'Lien'])
            ->add('price', TextType::class, ['label' => 'Prix'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('picture', EntityType::class, [
                'class' => Picture::class,
                'choice_label' => 'name',
                'label' => 'Image',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
