<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegionType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Dakar' => 'Dakar',
							'Diourbel' => 'Diourbel',
                            'Thies' => 'Thies',
                            'Kaolack' => 'Kaolack',
                            'Kaffrine' => 'Kaffrine',
                            'Fatick' => 'Fatick',
                            'Saint Louis' => 'Saint Louis',
                            'Louga' => 'Louga',
                            'Matam' => 'Matam',
                            'Ziguinchor' => 'Ziguinchor',
                            'Kolda' => 'Kolda',
                            'Sedhiou' => 'Sedhiou',
                            'Tambacounda' => 'Tambacounda',
                            'Kedougou' => 'Kedougou',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}