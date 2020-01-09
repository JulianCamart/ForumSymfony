<?php

namespace App\Form;

use App\Entity\ThreadTable;
use App\Entity\ForumTable;
use App\Entity\CatTable;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NewThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('ThreadName')
            ->add('ThreadText');          
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ThreadTable::class,
        ]);
    }
}