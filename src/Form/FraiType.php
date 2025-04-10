<?php

namespace App\Form;

use App\Entity\Frai;
use App\Entity\AvanceFrai;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as DoctrineEntityType;

class FraiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $frai = $options['data'];

        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant du frais',
                'required' => true,
                'scale' => 2,
                'attr' => ['step' => '0.01'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de frais',
                'required' => true,
            ])
            ->add('date_depense', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la dépense',
                'required' => true,
            ]);

        if ($frai->getId() === null) {
            $builder->add('pdf', FileType::class, [
                'label' => 'Justificatif PDF',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier PDF valide.',
                    ])
                ],
            ]);
        } elseif ($frai->getPdf() !== null) {
            $builder->add('pdf', FileType::class, [
                'label' => 'Justificatif PDF (non modifiable)',
                'mapped' => false,
                'required' => false,
                'attr' => ['disabled' => 'disabled'],
            ]);
        }

        $builder->add('avanceFrai', DoctrineEntityType::class, [
            'class' => AvanceFrai::class,
            'choice_label' => function (AvanceFrai $avanceFrai) {
                return 'Avance #' . $avanceFrai->getId() . ' - ' . $avanceFrai->getMotif();
            },
            'label' => 'Avance associée',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Frai::class,
        ]);
    }
}
