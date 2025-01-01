<?php

namespace App\Form;

use App\Entity\Banque;
use App\Entity\Categorie;
use App\Entity\Depense;
use App\Form\Type\VerserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez une ligne de depense', 
                'required' => true, 
            ])
            ->add('type', VerserType::class,array('placeholder' => 'Type de Paiement'))
            ->add('montant')
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
            'data_class' => Depense::class,
        ]);
    }
}
