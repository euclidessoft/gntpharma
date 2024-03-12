<?php

namespace App\Form;

use App\Entity\Livrer;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivrerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livreur', EntityType::class,
                array( 'class' => User::class,
                    'choice_label' => function($user){
                        return $user->getNom(). ' ' .$user->getPrenom();
                    },
                    'multiple' => false,
                    'query_builder' => function(UserRepository $repository) { return $repository->livreur(); },
                    'placeholder' => 'Selectionnez Livreur *',
                    'label' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livrer::class,
        ]);
    }
}
