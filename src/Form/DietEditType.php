<?php

namespace App\Form;

use App\Entity\Dietetician;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DietEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthday')
            ->add('adeli')
            ->add('user')
            ->add('specializations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dietetician::class,
        ]);
    }
}
