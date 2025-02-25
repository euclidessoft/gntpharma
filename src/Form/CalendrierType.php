<?php

namespace App\Form;

use App\Entity\Calendrier;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class,[
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class,[
                'widget' => 'single_text',
            ])
            ->add('employe',EntityType::class,[
                'class' => Employe::class,
                'choice_label' => function(Employe $employe){
                    return $employe->getNom() . ' ' . $employe->getPrenom();
                },
                'placeholder' => 'Choisissez l\'employé',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}
