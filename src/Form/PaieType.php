<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Paie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mois',DateType::class,[
                'widget' => 'single_text',
            ])
            ->add('employe',EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $employe){
                    return $employe->getNom().' '.$employe->getPrenom();
                },
                'placeholder' => 'Sélectionnez un employé',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paie::class,
        ]);
    }
}
