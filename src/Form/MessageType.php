<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('message', TextareaType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('recipients', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function($recipients){ return $recipients->getPrenom().' '.$recipients->getNom();}, // ou une autre propriété
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'label' => 'Destinataires'
            ])
            //->add('envoyer', SubmitType::class, [
              //  "attr" => [
                //    "class" => "btn btn-green"
              //  ]
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
