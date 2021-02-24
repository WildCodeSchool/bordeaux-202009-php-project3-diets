<?php

namespace App\Form;

use App\Entity\Dietetician;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DieteticianType extends AbstractType
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
            ->add('adeli', IntegerType::class, [
                'label' => 'Numéro ADELI *',
                'required' => false,
            ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dietetician::class,
        ]);
    }
}
