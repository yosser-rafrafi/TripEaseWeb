<?php

namespace App\Form;

use App\Entity\AvanceFrai;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AvanceFraiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantDemande', NumberType::class, [  // propriété camelCase
                'label' => 'Montant demandé',
                'scale' => 2,
                'attr'  => ['step' => '0.01'],
            ])
            ->add('devise', ChoiceType::class, [
                'label'   => 'Devise',
                'choices' => array_combine(
                    $options['currencies'],
                    $options['currencies']                  
                ),
                'required' => true,
            ])
            ->add('motif', TextareaType::class, [
                'label' => 'Motif',
                'attr'  => ['rows' => 5],
            ])
            ->add('typeAvance', ChoiceType::class, [
                'label'   => 'Type d\'avance',
                'choices' => [
                    'Avance sur frais' => 'avance',
                    'Transport'        => 'Transport',
                    'Hébergement'      => 'Hébergement',
                    'Repas'            => 'Repas',
                    'Autre'            => 'Autre',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceFrai::class,
            'currencies' => [],  // option pour la liste dynamique
        ]);
    }
}
