<?php

namespace App\Form;

use App\Entity\Expertise;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('lastname')
            ->add('firstname')
            ->add('birthday')
            ->add('adeli')
            ->add('address')
            ->add('phone')
            ->add('website')
            ->add('expertise', EntityType::class, [
                'class' => Expertise::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
           /* ->add('picture')*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
