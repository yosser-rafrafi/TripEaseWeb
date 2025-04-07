<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_contenu')
            ->add('contenu')
            ->add('date_creation', null, [
                'widget' => 'single_text'
            ])
            ->add('media_url')
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statut::class,
        ]);
    }
}
