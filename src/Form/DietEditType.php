<?php

namespace App\Form;

use App\Entity\Dietetician;
use App\Entity\Specialization;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DietEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date d\'anniversaire *',
                'years' => range(1920, 2020, 1),
                'format' => 'dd-MM-yyyy',
                'required' => true,
            ])
            ->add('adeli', NumberType::class, [
                'label' => 'Numéro ADELI *',
                'required' => false,
                'attr' => [
                    'minlength' => 9,
                    'maxlength' => 9
                ]
            ])
            ->add('specializations', EntityType::class, [
                'class' => Specialization::class,
                'required' => false,
                'label' => 'Cochez si vous avez une ou des spécialisations ?',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dietetician::class,
        ]);
    }
}
