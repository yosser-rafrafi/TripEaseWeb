<?php

namespace App\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination')
            ->add('date_depart', null, [
                'widget' => 'single_text'
            ])
            ->add('date_retour', null, [
                'widget' => 'single_text'
            ])
            ->add('budget')
            ->add('etat')
            ->add('title')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // ou un autre champ identifiable
            ])
            
            ->add('numeroVol')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
