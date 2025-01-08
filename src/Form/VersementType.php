<?php

namespace App\Form;

use App\Entity\Banque;
use App\Entity\Versement;
use App\Form\Type\VerserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', VerserType::class,array('placeholder' => 'Type de Paiement'))
            ->add('banque',EntityType::class,[
                'class' => Banque::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez une banque',
                'required' => true,
            ])
            ->add('numero')
            ->add('montant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
        ]);
    }
}
