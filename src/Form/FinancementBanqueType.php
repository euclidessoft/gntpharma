<?php

namespace App\Form;

use App\Entity\Banque;
use App\Entity\Financement;
use App\Form\Type\VerserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinancementBanqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compte')
            ->add('motif')
            ->add('montant')
            ->add('taux')
            ->add('banque',EntityType::class,[
                'class' => Banque::class,
                'choice_label' => "nom",
                'placeholder' => "Sélectionnez une banque",
                'required' => true,
            ])
            ->add('numero')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Financement::class,
        ]);
    }
}
