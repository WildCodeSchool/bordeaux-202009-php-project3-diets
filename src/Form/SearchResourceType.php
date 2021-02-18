<?php

namespace App\Form;

use App\Entity\Pathology;
use App\Entity\ResourceFormat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
            'label' => false,
            'required' => false,
            ])
            ->add('pathology', EntityType::class, [
                'class' => Pathology::class,
                'choice_label' => 'identifier',
                'multiple' => false,
                'expanded' => false,
                'required'   => false,
                'label'    => ' ',
                'placeholder' => 'catégorie',
            ])
            ->add('format', EntityType::class, [
                'class' => ResourceFormat::class,
                'choice_label' => 'identifier',
                'multiple' => false,
                'expanded' => false,
                'required'   => false,
                'label'    => ' ',
                'placeholder' => 'support',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
