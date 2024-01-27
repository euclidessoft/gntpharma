<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\SexeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
      
        
        ->add('adresse', null,['label' => 'Adresse'])
        ->add('prenom', null,['label' => 'Prenom'])
        ->add('nom', null,['label' => 'Nom'])
        //->add('datenaiss', DateType::class, array( 'label' => "Date de naissance",'widget' => 'single_text','attr' => ['title' => 'Date de naissance'],))
        ->add('fonction', ChoiceType::class, [
            'choices' => User::jobs,
            'placeholder' => 'types d\'utilisateur *',
            'label' => false,
            'required' => true
        ])

            ->add('phone', null,['label' => false])
            ->add('email', null,['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
