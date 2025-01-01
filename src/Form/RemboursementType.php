<?php

namespace App\Form;

use App\Entity\Banque;
use App\Entity\Financement;
use App\Entity\Remboursement;
use App\Form\Type\VerserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemboursementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('libele')
            ->add('type',VerserType::class,array('placeholder' => 'Type de Paiement'))
            ->add('financement', EntityType::class, [
                'class' => Financement::class,
                'choice_label' => 'motif',
                'placeholder' => 'Sélectionnez un financement', 
                'required' => true, 
            ])
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
            'data_class' => Remboursement::class,
        ]);
    }
}
