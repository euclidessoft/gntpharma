<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
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
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('phone')
            ->add('adresse')
            ->add('department')
            ->add('Date_naissance', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu_naissance')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Sexe',
            ])
            ->add('civilite',ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'celibataire',
                    'Marié(e)' => 'marie',
                    'Divorcé(e)' => 'divorce',
                    'Veuf' => 'veuf',
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'civilite',
            ])
            ->add('hiredate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de password'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
