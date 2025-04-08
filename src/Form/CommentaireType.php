<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Statut;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire_parent_id')
<<<<<<< HEAD
            ->add('contenu')
=======
            ->add('contenu', TextareaType::class, [
                'required' => true,
                'label' => 'Contenu',
                'attr' => ['minlength' => 1]
            ])
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
            ->add('date_publication', null, [
                'widget' => 'single_text'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
<<<<<<< HEAD
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
'choice_label' => 'id',
            ])
            ->add('statuts', EntityType::class, [
                'class' => Statut::class,
'choice_label' => 'id',
'multiple' => true,
            ])
=======
           
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
