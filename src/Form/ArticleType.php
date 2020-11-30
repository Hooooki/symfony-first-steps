<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de l\'article'
                ]
            ])
            ->add('author', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Auteur'
                ]
            ])
            ->add('content', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Contenu de l\'article',
                    'rows' => 9
                ]
            ])
            ->add('category', null, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 9
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
