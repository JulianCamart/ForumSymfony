<?php

namespace App\Form;

use App\Entity\ThreadTable;
use App\Entity\ForumTable;
use App\Entity\CatTable;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ModoEditThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('forum', EntityType::class, [
                'class' => ForumTable::class,
                'placeholder' => 'Forum du Topic',
                'choice_label' => 'ForumName' ,
            ]);
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ThreadTable::class,
        ]);
    }
}