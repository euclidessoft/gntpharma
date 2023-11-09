<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Eleve;
use App\Entity\Periode;
use App\Entity\Note;
use App\Entity\AnneeScolaire;
use Doctrine\ORM\EntityManagerInterface;

class Compos extends AbstractExtension
{
    private $em;
   
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            // on déclare notre fonction.
            // Le 1er paramètre est le nom de la fonction utilisée dans notre template
            // le 2ème est un tableau dont le 1er élément représente la classe où trouver la fonction associée (en l'occurence $this, c'est à dire cette classe puisque notre fonction est déclarée un peu plus bas). Et le 2ème élément du tableau est le nom de la fonction associée qui sera appelée lorsque nous l'utiliserons dans notre template.
            new TwigFunction('compos', array($this, 'getNotes')),
        );
    }

    // chemin relatif de notre fichier en paramètre
    public function getNotes(Eleve $eleve,Periode $periode, AnneeScolaire $annee)
    {
        
        return $this->em->getRepository(Note::class)->compos($eleve->getId(),$periode->getId(), $annee->getId());
    }
}