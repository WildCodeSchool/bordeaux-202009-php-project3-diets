<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('link')
            ->add('dateStart')
            ->add('dateEnd')
            ->add('price')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('eventIsValidated')
            ->add('eventFormat')
            ->add('picture', EntityType::class, [
                'class' => EventType::class,
                'choice_label' => 'name',
            ])
            ->add('registeredEvent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
