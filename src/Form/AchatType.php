<?php

namespace App\Form;

use App\Entity\Achat;
use App\Entity\Banque;
use App\Entity\Fournisseur;
use App\Form\Type\VerserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('libele')
            ->add('reference')
            ->add('fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'choice_label' => 'designation',
                'placeholder' => 'Sélectionnez un fournisseur',
                'required' => true,
            ])
            ->add('type', VerserType::class,array('placeholder' => 'Type de Paiement'))
            ->add('banque',EntityType::class, [
                'class' => Banque::class,
                'choice_label' => 'Nom',
                'placeholder' => "Sélectionnez le nom de la banque",
                'required' => true,
            ])
            ->add('numero')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
