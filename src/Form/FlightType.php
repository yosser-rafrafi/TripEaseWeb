<?php

namespace App\Form;

use App\Entity\Flight;
use App\Entity\Voyage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('flight_number')
            ->add('airline')
            ->add('departure_datetime', null, [
                'widget' => 'single_text'
            ])
            ->add('departure_airport')
            ->add('arrival_datetime', null, [
                'widget' => 'single_text'
            ])
            ->add('arrival_airport')
            ->add('voyage', EntityType::class, [
                'class' => Voyage::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
        ]);
    }
}
