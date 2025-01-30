<?php

namespace App\Form;

use App\Entity\Conges;
use App\Entity\TypeConge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeCongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('type', EntityType::class, [
                'class' => TypeConge::class,
                'choice_label' => 'nom',
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
