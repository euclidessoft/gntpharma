<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LinkType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
						'choices' => array(
							'Papa' => 'Papa',
							'Maman' => 'Maman',
							'Epoux' => 'Epoux',
							'Epouse' => 'Epouse',
							'Fils' => 'Fils',
							'Fille' => 'Fille',
							'Frère' => 'Frère',
							'Soeur' => 'Soeur',
							'Nièce' => 'Nièce',
							'Neveu' => 'Neveu',
							'Tante' => 'Tante',
							'Oncle' => 'Oncle',
							'Cousin' => 'Cousin',
							'Cousine' => 'Cousine',
							'Beau-père' => 'Beau-père',
							'Belle-mère' => 'Belle-mère',
							'Beau-fils' => 'Beau-fils',
							'Belle-fille' => 'Belle-fille',
							'Ami' => 'Ami',
							'Amie' => 'Amie',
							'Voisin' => 'Voisin',
							'Voisine' => 'Voisine',
							)
						));
	}
	public function getParent()
	{
		return ChoiceType::class;
	}

}
