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
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la destination',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('date_depart', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date de départ',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('date_retour', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date de retour',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('budget', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le budget',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('etat', null, [
                'attr' => [
                    'class' => 'form-control',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre',
                    'novalidate' => 'novalidate'
                ]
            ])
            ->add('numeroVol', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le numéro de vol',
                    'novalidate' => 'novalidate'
                ]
            ])
            // Affecter des utilisateurs au voyage
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // ou autre propriété
                'multiple' => true,  // Permet de sélectionner plusieurs employés
                'expanded' => true,   // Affiche des cases à cocher plutôt qu'une liste déroulante
                'label' => 'Sélectionner les employés',
                'attr' => [
                    'class' => 'form-check-input'
                ]
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
