<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Paie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreBulletinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $mois = [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];

        $annees = array_combine(
            $years = range(date('Y'), 2025),
            $years
        );

        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $employe) {
                    return $employe->getNom() . ' ' . $employe->getPrenom();
                },
                'placeholder' => 'Sélectionnez les employés',
                'multiple' => false,
                'required' => false,
                'expanded' => false,
            ])
            ->add('mois', ChoiceType::class, [
                'choices' => $mois,
                'label' => 'Mois :',
                'attr' => ['class' => 'form-control w-auto'],
                'required' => false,
                'placeholder' => 'Tous les mois',
            ])
            ->add('annee', ChoiceType::class, [
                'choices' => $annees,
                'label' => 'Année :',
                'attr' => ['class' => 'form-control w-auto'],
                'required' => false,
                'placeholder' => 'Toutes les années',
            ])
            ->add('filtrer', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => ['class' => 'btn btn-success w-100'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
        ]);
    }
}
