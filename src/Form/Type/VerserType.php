<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VerserType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Espece' => 'Espece',
							'Cheque' => 'Cheque',
							'Virement' => 'Virement',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}