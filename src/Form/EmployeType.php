<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\SexeType;
use App\Entity\Departement;
use App\Entity\Employe;
use App\Entity\Poste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('prenom')
            ->add('nom')
            ->add('nationalite')
            ->add('email')
            ->add('phone')
            ->add('adresse')
            ->add('poste', EntityType::class, [
                'class' => Poste::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un poste',
                'required' => true,
            ])
            ->add('Date_naissance', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu_naissance')
            ->add('nationalite')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'placeholder' => 'Choisissez le sexe',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Sexe',
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'celibataire',
                    'Marié(e)' => 'marie',
                    'Divorcé(e)' => 'divorce',
                    'Veuf' => 'veuf',
                ],
                'placeholder' => 'Choisissez une civilité',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'civilite',
            ])
            ->add('hiredate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('fonction', ChoiceType::class, [
                'choices' => User::jobs,
                'placeholder' => 'Types d\'utilisateur *',
                'label' => false,
                'required' => true
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de password'])
            ->add('fonction', ChoiceType::class, [
                'choices' => User::jobs,
                'placeholder' => 'types d\'utilisateur *',
                'label' => false,
                'required' => true
            ])
            ->add('visite')
            ->add('ethnie')
            ->add('bloodgroup', ChoiceType::class, [
                'choices' =>[
                    'A' => 'A',
                    'B' => 'B',
                    'AB' => 'AB',
                    'O' => 'O',
                ],
                'placeholder' => 'Sélectionnez le groupe sanguin'
            ])
            ->add('diabete', CheckboxType::class, [
                'label' => 'Handicap',
                'required' => false,
            ])
            ->add('handicap', CheckboxType::class, [
                'label' => 'Handicap',
                'required' => false,
            ])
            ->add('hypo', CheckboxType::class, [
                'label' => 'Hypoglycémie',
                'required' => false,
            ])
            ->add('hyper', CheckboxType::class, [
                'label' => 'Hypertension',
                'required' => false,
            ])

            ->add('famillyname')
            ->add('famillylink')
            ->add('famillyphone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
