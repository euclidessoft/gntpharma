<?php

namespace App\Form;

use App\Entity\Conges;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprouverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $conge = $options['conge'];
        $builder
            ->add('dateDebutAccorder', DateType::class, [
//                'data' => $conge->getDateDebut(),
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('dateFinAccorder', DateType::class, [
//                'data' => $conge->getDateFin(),
                'widget' => 'single_text',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conges::class,
            'conge' => null,
        ]);
    }
}
