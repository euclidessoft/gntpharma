<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Reclamation;
use App\Repository\CommandeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $id = $options['id'];
        $builder
            ->add('motif', ChoiceType::class, [
                'choices' => Reclamation::reclamer,
                'placeholder' => 'Selectionnez un motif *',
                'label' => false,
                'required' => false
            ])
            ->add('commande', EntityType::class,
                array( 'class' => Commande::class,
                    'choice_label' => 'ref',
                    'multiple' => false,
                    'query_builder' => function(CommandeRepository $repository) use ($id) { return $repository->current($id); },
                    'placeholder' => 'Selectionnez commande *'))
            ->add('commentaire', null,[])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            'id' => null,
        ]);
    }
}
