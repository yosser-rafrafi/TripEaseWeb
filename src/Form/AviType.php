<?php

namespace App\Form;

use App\Entity\Avi;
use App\Entity\Hotel;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AviType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre avis',
                'required' => true,
            ])
            ->add('note', IntegerType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'style' => 'display:none;', // On le cache, car on utilise notre propre affichage étoiles
                ],
            ])
            
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avi::class,
        ]);
    }
}
