<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class adresseType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Louga' => 'Louga',
							'Environs de Louga' => 'Environs de Louga',
                            'Hors Louga' => 'Hors Louga',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}