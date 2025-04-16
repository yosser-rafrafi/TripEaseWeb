<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email',
                    ]),
                    new Email([
                        'message' => 'Please enter a valid email address',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Mot de passe']
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Répétez le mot de passe']
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Last Name'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your last name',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'First Name'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your first name',
                    ]),
                ],
            ])
            ->add('numTel', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Phone Number'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your phone number',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Phone number must be exactly 8 digits',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Phone number must contain only digits',
                    ]),
                ],
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Address'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your address',
                    ]),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Manager' => 'MANAGER',
                    'Employee' => 'EMPLOYE'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a role',
                    ]),
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