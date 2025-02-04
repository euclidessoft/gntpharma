<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BloodGroupType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'A+' => 'A+',
							'A-' => 'A-',
							'AB+' => 'AB+',
							'AB-' => 'AB-',
							'B+' => 'B+',
							'B-' => 'B-',
							'O+' => 'O+',
							'O-' => 'O-',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}