<?php

namespace App\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination', null, [
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('date_depart', null, [
                'widget' => 'single_text',
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('date_retour', null, [
                'widget' => 'single_text',
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('budget', null, [
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('etat', null, [
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('title', null, [
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'attr' => ['novalidate' => 'novalidate']
            ])
            ->add('numeroVol', null, [
                'attr' => ['novalidate' => 'novalidate']
            ])

            // Affecter des utilisateurs au voyage
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // ou autre propriété
                'multiple' => true,  // Permet de sélectionner plusieurs employés
                'expanded' => true,   // Affiche des cases à cocher plutôt qu'une liste déroulante
                'label' => 'Sélectionner les employés',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
