<?php
namespace App\Form;

use App\Entity\Reservationtransport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ReservationtransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_reservation', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
            'empty_data' => null,
            'label' => 'Date de réservation',
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
        ->add('date_reservation_fin', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
            'empty_data' => null,
            'label' => 'Date de fin de réservation',
            'attr' => ['class' => 'form-control custom-input'],
            'constraints' => [
                new Assert\Type([
                    'type' => \DateTimeInterface::class,
                    'message' => 'Le format de la date de fin est invalide.',
                ]),
                new Callback([
                    'callback' => function ($dateReservationFin, ExecutionContextInterface $context) {
                        $form = $context->getRoot();
                        $reservation = $form->getData();
                        
                        // Skip validation if either date is null
                        if (null === $dateReservationFin || null === $reservation->getDateReservation()) {
                            return;
                        }
                        
                        if ($dateReservationFin <= $reservation->getDateReservation()) {
                            $context->buildViolation('La date de fin doit être après la date de réservation.')
                                ->atPath('date_reservation_fin')
                                ->addViolation();
                        }
                    },
                ]),
            ],
        ])
            ->add('type_reservation', TextType::class, [
                'required' => true,
                'label' => 'Type de réservation', 
            ])
            ->add('priorite_reservation', ChoiceType::class, [
                'choices' => [
                    'Haut' => 'High',
                    'Moyenne' => 'Medium',
                    'Faible' => 'Low',
                ],
                'required' => true,
                'label' => 'Priorité de réservation', 
            ])
            ->add('notes_reservation', TextareaType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Ajouter des notes pour la réservation'], 
                'label' => 'Notes de réservation', 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationtransport::class,
        ]);
    }
}
