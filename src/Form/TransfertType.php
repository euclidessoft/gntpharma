<?php

namespace App\Form;

use App\Entity\Transfert;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('source', ChoiceType::class,[
                'choices' => [
                    'Caisse' => 'Caisse',
                    'Banque' => 'Banque',
                ],
                'placeholder' => 'Sélectionnez la source du transfert', 
                'required' => true,
                'label' => 'Source',
            ])
            
            ->add('destination', ChoiceType::class,[
                'choices' => [
                    'Banque' => 'Banque',
                    'Caisse' => 'Caisse',
                ],
                'placeholder' => 'Sélectionnez la destination du transfert', 
                'required' => true,
                'label' => 'Source',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function($user){
                    return $user->getNom(). ' ' .$user->getPrenom();
                },
                'placeholder' => 'Sélectionnez le nom et prenom',
                'multiple' => false,
                'required' => true,
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transfert::class,
        ]);
    }
}
