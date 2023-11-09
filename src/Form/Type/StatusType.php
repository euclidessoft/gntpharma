<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StatusType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Professionnel' => 'Professionnel',
							'Etudiant' => 'Etudiant',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}