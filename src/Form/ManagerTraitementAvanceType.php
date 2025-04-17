<?php

// src/Form/ManagerTraitementAvanceType.php

namespace App\Form;

use App\Entity\AvanceFrai;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagerTraitementAvanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_accorde', MoneyType::class, [
                'label' => 'Montant accordé',
                
            ])
            ->add('commentaire_manager', TextareaType::class, [
                'label' => 'Commentaire du manager',
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceFrai::class,
        ]);
    }
}

