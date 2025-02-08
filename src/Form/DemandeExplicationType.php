<?php

namespace App\Form;

use App\Entity\DemandeExplication;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeExplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('details')
            ->add('dateIncident', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('employe',EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $employe){
                    return $employe->getNom().' '.$employe->getPrenom();
                },
                'multiple' => true,
                'required' => true,
                'placeholder' => 'Selectionnez l\'employé',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeExplication::class,
        ]);
    }
}
