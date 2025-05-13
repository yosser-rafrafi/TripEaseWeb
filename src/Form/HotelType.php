<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l’hôtel',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: Hôtel Le Palace'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: 123 Rue Centrale'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: Paris'],
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: France'],
            ])
            ->add('nombre_etoiles', IntegerType::class, [
                'label' => 'Nombre d’étoiles',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: 5'],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: +33 1 23 45 67 89'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: contact@hotel.com'],
            ])
            ->add('site_web', UrlType::class, [
                'label' => 'Site Web',
                'attr' => ['class' => 'form-control p-3 rounded-3', 'placeholder' => 'Ex: https://www.hotel.com'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
