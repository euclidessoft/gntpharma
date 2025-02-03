<?php

namespace App\Form;

use App\Entity\ReponseAbsence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ReponseAbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cause')
            ->add('file', FileType::class, [
                'label' => 'Fichier (PDF, JPEG, PNG)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un document valide (PDF, JPG, PNG)',
                    ])
                ],
                'attr' => [
                    'class' => 'custom-file-input',
                    'onchange' => 'updateFileName(this)'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseAbsence::class,
            'attr' => ['id' => 'reponse-absence-form']
        ]);
    }
}
