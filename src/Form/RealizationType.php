<?php

namespace App\Form;

use App\Entity\Realization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tag;

class RealizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('contents', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('date')
            ->add('online')
            ->add('description', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('metadescription')
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Realization::class,
            'translation_domain' => 'forms',
        ]);
    }
}
