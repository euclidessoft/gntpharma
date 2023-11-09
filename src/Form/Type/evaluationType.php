<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class evaluationType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Favorable' => 'Favorable',
							'Defavorable' => 'Defavorable',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}