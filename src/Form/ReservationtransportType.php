<?php
namespace App\Form;

use App\Entity\Reservationtransport;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotNull;

class ReservationtransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Transport selection
            ->add('transport', EntityType::class, [
                'class' => Transport::class,
                'choice_label' => 'transportName', // Customize this based on your Transport entity
                'placeholder' => 'Choose a transport',
                'constraints' => [new NotNull(['message' => 'Please select a transport.'])],
            ])
            // Reservation Date (using DateType to show a calendar)
            ->add('date_reservation', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Select a reservation date',
                ],
                'constraints' => [new NotNull(['message' => 'Reservation date is required.'])],
            ])
            // Type of reservation
            ->add('type_reservation', TextType::class, [
                'constraints' => [new NotNull(['message' => 'Reservation type is required.'])],
            ])
            // Priority selection
            ->add('priorite_reservation', ChoiceType::class, [
                'choices' => [
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ],
                'constraints' => [new NotNull(['message' => 'Reservation priority is required.'])],
            ])
            // Notes (optional)
            ->add('notes_reservation', TextareaType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Add any notes for the reservation'],
            ])
            // Submit button
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Reservation',
                'attr' => ['class' => 'btn btn-primary'] // Add custom classes for styling
            ]);
    }

    
}
