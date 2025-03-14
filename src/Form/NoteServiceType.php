<?php

namespace App\Form;

use App\Entity\NoteService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('detail')
            ->add('dateDebut',DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('dateFin',DateType::class,[
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteService::class,
        ]);
    }
}
