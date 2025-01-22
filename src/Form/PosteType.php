<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Poste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Selectionnez un département',
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
        ]);
    }
}
