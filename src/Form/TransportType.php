<?php

namespace App\Form;

use App\Entity\Transport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;  
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transport_name', TextType::class, [
                'label' => 'Transport Name',
                'required' => true,
            ])
            ->add('transport_description', TextareaType::class, [
                'label' => 'Transport Description',
                'required' => true,
            ])
            ->add('transport_location', TextType::class, [
                'label' => 'Location',
                'required' => true,
            ])
            ->add('transport_agence', ChoiceType::class, [
                'choices' => [
                    'Elite Logistics Group' => 'Elite Logistics Group',
                    'Prime Fleet Solutions' => 'Prime Fleet Solutions',
                    'Pioneer Transit Services' => 'Pioneer Transit Services',
                    'Velocity Transport Co.' => 'Velocity Transport Co.',
                    'EcoDrive Electric Rentals' => 'EcoDrive Electric Rentals', 
                ],
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Choisir une agence',
            ])
            ->add('transport_type', ChoiceType::class, [
                'choices' => [
                    'Taxi' => 'taxi',
                    'Voiture' => 'voiture',
                    'Véhicule Utilitaire' => 'utilitaire',
                    'Voiture Électrique' => 'electrique',
                ],
                'label' => 'Type de Transport',
            ])
            ->add('transport_pays', TextType::class, [
                'label' => 'Country',
                'required' => true,
            ])
            ->add('transport_prix', NumberType::class, [
                'label' => 'Price',
                'required' => true,
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'required' => false,
                'scale' => 6,
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}
