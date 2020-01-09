<?php

namespace App\Form;

use App\Entity\ForumTable;
use App\Entity\CatTable;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ForumName')
            ->add('ForumDescription')
            ->add('category', EntityType::class, [
                'class' => CatTable::class,
                'placeholder' => 'Choisissez une Catégorie',
                'choice_label' => 'CatName'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumTable::class,
        ]);
    }
}
