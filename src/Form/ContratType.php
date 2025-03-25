<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Employe;
use App\Entity\TypeContrat;
use Dom\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe',EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $employe){
                    return $employe->getNom().' '.$employe->getPrenom();
                },
                'placeholder' => 'Choisissez l\'employé',
                'required' => true,
            ])
            ->add('typeContrat',EntityType::class, [
                'class' => TypeContrat::class,
                'choice_label' => 'type',
                'placeholder' => 'Sélectionner un type de contrat',
            ])
            ->add('dateDebut',DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateFin',DateType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
