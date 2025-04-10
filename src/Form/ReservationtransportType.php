<?php

namespace App\Form;

use App\Entity\Reservationtransport;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationtransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transport', EntityType::class, [
                'class' => Transport::class,
                'choice_label' => 'transportName', // Customize this based on your Transport entity
                'placeholder' => 'Choose a transport',
                'required' => true,
                'disabled' => true, // To disable the selection and keep the transport hidden
            ])
            ->add('date_reservation', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,  // Make it optional
                'empty_data' => null, // Allow null if no date is provided
                'constraints' => [
                    new Assert\Type([
                        'type' => \DateTimeInterface::class,
                        'message' => 'Le format de la date est invalide.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de réservation doit être dans le futur.',
                    ]),
                ],
            ])
            
            ->add('type_reservation', TextType::class, [
                'required' => true,
            ])
            ->add('priorite_reservation', ChoiceType::class, [
                'choices' => [
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ],
                'required' => true,
            ])
            ->add('notes_reservation', TextareaType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Add any notes for the reservation'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationtransport::class,
        ]);
    }
}
