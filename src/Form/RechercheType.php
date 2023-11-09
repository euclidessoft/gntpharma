<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Recherche;
use App\Entity\Zone;
use App\Entity\Cellule;
use App\Entity\Region;
use App\Form\Type\BloodGroupType;
use App\Form\Type\SexeType;
use App\Form\Type\RegionType;
use App\Form\Type\StatusType;
use App\Form\Type\AnneeType;
use App\Form\Type\SituationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('cellule', EntityType::class, [
            'class' => Cellule::class,
            'choice_label' => 'nom',
            'required' => false,
            'label' => 'Cellule'
        ])
        ->add('profession', ChoiceType::class, [
            'choices' => User::jobs,
            'required' => false,
            'label' => 'Professions'
        ])
        ->add('sexe', SexeType::class,[
            'required' => false,
            'label' => 'Civilite'
            ])
        ->add('status', StatusType::class,[
            'required' => false,
            'label' => 'Status'
            ])
        ->add('situation', SituationType::class,[
            'required' => false,
            'label' => 'Situation Matrimoniale'
            ])
        ->add('annee', AnneeType::class,[
            'required' => false,
            'label' => 'Annee d\'adhesion'
            ])
        ->add('bloodgroup', BloodGroupType::class,[
            'required' => false,
            'label' => 'Groupe Sanguin'
            ])
        ->add('specialite', null,[
            'required' => false,
            'label' => 'Specialite'
        ])
        ->add('region', EntityType::class, [
            'class' => Region::class,
            'choice_label' => 'nom',
            'required' => false,
            'label' => 'Region'
        ])
        ->add('departement', null,[
            'required' => false,
            'label' => 'Departement'
        ])
        ->add('ville', null,[
            'required' => false,
            'label' => 'Ville'
        ])
        ->add('etablissement', null,[
            'required' => false,
            'label' => 'Etablissement'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recherche::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
    
    public function getBlockPrefix()
    {
        return '';
    }
}
