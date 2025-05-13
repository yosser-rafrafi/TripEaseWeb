<?php

namespace App\Form;


use App\Entity\User;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder 
            ->add('destination', null, [
               
            ])
            
            ->add('date_depart', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'), // Pour type="datetime-local"
                ],
            ])
            ->add('date_retour', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'), // Pour type="datetime-local"
                ],
            ])
            
            ->add('budget', null, [
               
            ])
           
            ->add('title', null, [
            
            ])
            ->add('numeroVol', null, [
                
            ])
        
            ->add('tempUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'multiple' => false, // Sélection simple
                'mapped' => false, // Ce champ n'est pas mappé à l'entité
                'label' => 'Sélectionner un employé',
                'attr' => [
                    'class' => 'form-control user-select',
                    'id' => 'user_select'
                ],
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.role LIKE :role')
                        ->setParameter('role', '%EMPLOYE%');
                }
            ])
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }


    
}
