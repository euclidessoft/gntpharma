<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\TypeDecision;
use App\Entity\TypeSanction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motif')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Avertissement' => 'Avertissement',
                    'Demande d\'explication' => 'Demande d\'explication',
                    'Sanction' => 'Sanction',
                ],
                'required' => true,
                'placeholder' => 'Sélectionnez le type de decision',
            ])
            ->add('demandes')
            ->add('typeSanction', EntityType::class, [
                'class' => TypeSanction::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Sélectionnez la sanction',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
