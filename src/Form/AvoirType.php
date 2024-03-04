<?php

namespace App\Form;

use App\Entity\Avoir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvoirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date')
            ->add('Montant')
//            ->add('admin')
//            ->add('client')
//            ->add('commande')
//            ->add('reclamation')
//            ->add('reste')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avoir::class,
        ]);
    }
}
