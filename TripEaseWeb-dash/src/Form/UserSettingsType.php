<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 50]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 50]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('numTel', TextType::class, [
                'label' => 'Phone Number',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Address',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'New Password',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Leave blank to keep current password'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Length(['min' => 6, 'max' => 4096]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 