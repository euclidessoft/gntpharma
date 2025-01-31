<?php

namespace App\Form;

use App\Entity\Conges;
use App\Entity\TypeConge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DemandeCongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La date de début est obligatoire.',
                    ]),
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La date de fin est obligatoire.',
                    ])
                ],
            ])
            ->add('description')
            ->add('type', EntityType::class, [
                'class' => TypeConge::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Sélectionnez le type de congé'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conges::class,
        ]);
    }
}
