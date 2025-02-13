<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Sanction;
use App\Entity\TypeSanction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $employe) {
                    return $employe->getNom() . ' ' . $employe->getPrenom();
                },
                'placeholder' => 'Choisissez le type de la sanction',
                'required' => true,
                'expanded' => false,
            ])
            ->add('description')
            ->add('typeSanction', EntityType::class, [
                'class' => TypeSanction::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez le type de la sanction',
                'required' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sanction::class,
        ]);
    }
}
