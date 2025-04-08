<?php
namespace App\Form;

use App\Entity\Reservationtransport;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationtransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Removed 'id_employe' field as it's set programmatically
            ->add('transport', EntityType::class, [
                'class' => Transport::class,
                'choice_label' => 'transportName', // or whatever field you want to show in the dropdown
                'placeholder' => 'Choose a transport',
            ])
            ->add('date_reservation')
            ->add('type_reservation')
            ->add('priorite_reservation')
            ->add('notes_reservation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationtransport::class,
        ]);
    }
}
