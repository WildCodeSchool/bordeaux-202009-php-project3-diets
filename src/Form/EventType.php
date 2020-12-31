<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventFormat;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
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
            ->add('eventFormat', EntityType::class, [
                'class' => EventFormat::class,
                'choice_label' => 'format',
            ])
            //->add('picture', EntityType::class, [
            //    'class' => Picture::class,
            //    'choice_label' => 'name',
            //])
            ->add('registeredEvent', EntityType::class, [
                'class' => RegisteredEvent::class,
                'choice_label' => 'isOrganizer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
