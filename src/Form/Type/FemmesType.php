<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FemmesType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'1' => '1',
							'2' => '2',
                            '3' => '3',
                            '4' => '4',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}