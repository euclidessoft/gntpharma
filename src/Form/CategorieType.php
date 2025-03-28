<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('compte')
            ->add('description')
            ->add('actif', ChoiceType::class, [
                'label' => 'Categorie de la ligne',
                'choices' => [
                    'Actif' => true,
                    'Passif' => false,
                ],
                'expanded' => true, // Affiche sous forme de boutons radio
                'multiple' => false, // Empêche la sélection multiple
                'required' => true, // Champ obligatoire
                'data' => true, // Définit "Actif" comme valeur par défaut
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
