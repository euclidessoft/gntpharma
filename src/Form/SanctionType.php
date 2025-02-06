<?php

namespace App\Form;

use App\Entity\Sanction;
use App\Entity\TypeSanction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motif')
            ->add('type',EntityType::class, [
                'class' => TypeSanction::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Sélectionnez le type de sanction',
            ])
            ->add('montant')
            ->add('demandes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sanction::class,
        ]);
    }
}
