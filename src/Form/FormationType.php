<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('lieu')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('datefin', Datetype::class, [
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Sélectionnez la date de début',
                ],
            ])
            ->add('contenu')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'En ligne' => 'En ligne',
                    'Présentiel' => "Présentiel",
                ],
                'placeholder' => 'Choisissez le type de formation',
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $employe) {
                    return $employe->getNom() . ' ' . $employe->getPrenom();
                },
                'placeholder' => 'Sélectionnez les employés',
                'multiple' => true,
                'required' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
