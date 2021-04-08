<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventFormat;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('link', TextType::class, ['label' => 'Lien'])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Début',
                'data'   => new \DateTime(),
                'date_widget' => 'single_text',
                'attr'   => ['min' => ( new \DateTime('now'))->format('YY-MM-dd--hh--ii')],
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Fin',
                'data'   => new \DateTime(),
                'date_widget' => 'single_text',
                'attr'   => ['min' => ( new \DateTime('now'))->format('YY-MM-dd--hh--ii')],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix (facultatif)',
                'required' => false,
            ])
            ->add('eventFormat', EntityType::class, [
                'class' => EventFormat::class,
                'choice_label' => 'format',
                'label' => 'Format',
            ])
            ->add('pictures', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
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
