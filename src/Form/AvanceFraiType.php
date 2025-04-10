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
            
            ->add('montant_demande', NumberType::class, [
                'label' => 'Montant demandé',
                'required' => true,
                'scale' => 2,  // Précise le nombre de décimales
                'attr' => ['step' => '0.01'],  // Permet d'accepter des décimales
            ])
            ->add('devise', ChoiceType::class, [
                'label' => 'Devise',
                'choices' => [
                    'EUR' => 'EUR',
                    'USD' => 'USD',
                    'TND' => 'TND',
                ],
                'required' => true,  // On peut ajouter "required" si c'est un champ obligatoire
            ])
            ->add('motif', TextareaType::class, [
                'label' => 'Motif',
                'required' => true,  // On peut ajouter "required" si c'est un champ obligatoire
                'attr' => ['rows' => 5], // Optionnel, pour définir la hauteur du textarea
            ])
            ->add('type_avance', ChoiceType::class, [
                'label' => 'Type d\'avance',
                'choices' => [
                    'Avance sur frais' => 'avance',
                    'Remboursement' => 'remboursement',
                ],
                'required' => true,  // On peut ajouter "required" si c'est un champ obligatoire
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceFrai::class,
        ]);
    }
}
