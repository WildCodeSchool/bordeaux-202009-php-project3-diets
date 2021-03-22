<?php


namespace App\Form;


use App\Entity\Pathology;
use App\Entity\Resource;
use App\Entity\ResourceFormat;
use App\Repository\ResourceFormatRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisioPaydType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien',
                'required' => true,
            ])
            ->add('pathology', EntityType::class, [
                'class' => Pathology::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Choisir une catégorie',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('resourceFiles', FileType::class, [
                'label' => 'Déposer un fichier à télécharger 
                (Si vous souhaitez rajouter un support que vos auditeurs pourront télécharger)',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Début',
                'data'   => new \DateTime(),
                'attr'   => ['min' => ( new \DateTime('now'))->format('YY-MM-dd--hh--ii')],
                'years' => range(2021, 2040, 1),
                'required' => false,
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Fin',
                'data'   => new \DateTime(),
                'attr'   => ['min' => ( new \DateTime('now'))->format('YY-MM-dd--hh--ii')],
                'years' => range(2021, 2040, 1),
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
        ]);
    }

}
