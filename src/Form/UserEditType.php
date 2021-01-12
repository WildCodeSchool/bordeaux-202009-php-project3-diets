<?php

namespace App\Form;

use App\Entity\Expertise;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('birthday', DateTimeType::class, [
                'label' => 'Date d\'anniversaire',
            ])
            ->add('adeli', TextType::class, [
                'label' => 'Numéro ADELI',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse de votre cabinet'
            ])
            ->add('phone', IntegerType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('website', TextType::class, [
                'label' => 'Votre site web',
            ])
            ->add('expertise', EntityType::class, [
                'label' => 'Votre domaine d\'expertise',
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
