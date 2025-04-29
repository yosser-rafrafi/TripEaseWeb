<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Hotel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Reservationhotel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationhotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hotel = $options['hotel'] ?? null; // Récupère l'hôtel pour filtrer les chambres

        $builder
        ->add('date_reservation', \Symfony\Component\Form\Extension\Core\Type\DateType::class, [
            'label' => 'Date de réservation',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'],
            'data' => new \DateTime(), // ⬅️ ici on met la date actuelle
            'constraints' => [
                new Assert\NotBlank(['message' => 'La date de réservation est obligatoire']),
            ],
        ])
        
            ->add('date_debut', \Symfony\Component\Form\Extension\Core\Type\DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('date_fin', \Symfony\Component\Form\Extension\Core\Type\DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Réservé' => 'Réservé',
                    'Annulé' => 'Annulé',
                    'Confirmé' => 'Confirmé',
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le statut est obligatoire']),
                ],
            ])

            
            
            ->add('hotel', EntityType::class, [
                'class' => Hotel::class,
                'choice_label' => 'nom', // Affiche le nom de l'hôtel au lieu de l'identifiant
                'label' => 'Hôtel',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un hôtel',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'hôtel est obligatoire']),
                ],
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choices' => $hotel ? $hotel->getChambres() : [], // Filtre les chambres en fonction de l'hôtel
                'choice_label' => 'type_chambre', // Affiche un label significatif (ex. type de chambre)
                'label' => 'Chambre',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une chambre',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La chambre est obligatoire']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationhotel::class,
            'hotel' => null, // Permet de passer un hôtel pour filtrer les chambres
        ]);
    }
}