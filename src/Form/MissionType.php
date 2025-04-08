<?php

namespace App\Form;

use App\Entity\Mission;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextType;

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('title')
            ->add('lieu')
=======
            ->add('title',null)
            ->add('lieu', TextType::class, [
                'required' => true,
            ])
            
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
            ->add('description')
            ->add('dateDebut', null, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', null, [
                'widget' => 'single_text'
            ])
            ->add('type')
            ->add('duree')
<<<<<<< HEAD
            ->add('voyageId')
=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
            ->add('userId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
