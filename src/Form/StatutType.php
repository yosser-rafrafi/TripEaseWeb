<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('type_contenu')
            ->add('contenu')
=======
            ->add('type_contenu', TextareaType::class, [
                'required' => true,
                'label' => 'Type contenu',
                'attr' => ['minlength' => 1],
                'empty_data' => '',

            ])->add('contenu', TextareaType::class, [
                'required' => true,
                'label' => 'Contenu',
                'attr' => ['minlength' => 1]
            ])
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
            ->add('date_creation', null, [
                'widget' => 'single_text'
            ])
            ->add('media_url')
            ->add('user', EntityType::class, [
                'class' => User::class,
<<<<<<< HEAD
'choice_label' => 'id',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
'multiple' => true,
            ])
=======
            'choice_label' => 'id',
            ])

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statut::class,
        ]);
    }
}
