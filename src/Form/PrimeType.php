<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Prime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('description')
            ->add('employe',EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $employe){
                    return $employe->getNom().' '.$employe->getPrenom();
                },
                'placeholder' => 'Sélectionnez un employé',
                'required' => true,
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prime::class,
        ]);
    }
}
