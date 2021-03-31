<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Intl\Countries;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        \Locale::setDefault('en');
        $countries = array_flip(Countries::getNames());
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email *',
                'required' => true,
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Pays *',
                'choices' => $countries,
                'placeholder' => 'Faites votre choix',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse de votre cabinet',
                'required' => false,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('website', TextType::class, [
                'label' => 'Votre site web',
                'required' => false,
            ])
            ->add('picture', PictureType::class, [
                'label' => 'Charger une image',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
