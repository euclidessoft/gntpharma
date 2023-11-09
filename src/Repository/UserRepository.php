<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Recherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function dispo(Recherche $recherche)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', true );
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getZone())
        {
            $query->AndWhere('a.zone = :zone')
            ->setParameter('zone', $recherche->getZone()->getId());
        }
        if($recherche->getCellule())
        {
            $query->AndWhere('a.cellule = :cellule')
            ->setParameter('cellule', $recherche->getCellule()->getId());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function dispoCellule(Recherche $recherche, $cellule)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', true );
        $query->AndWhere('a.cellule = :cellule')
        ->setParameter('cellule', $cellule);
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getZone())
        {
            $query->AndWhere('a.zone = :zone')
            ->setParameter('zone', $recherche->getZone()->getId());
        }
        if($recherche->getCellule())
        {
            $query->AndWhere('a.cellule = :cellule')
            ->setParameter('cellule', $recherche->getCellule()->getId());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function dispoZone(Recherche $recherche, $zone)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', true );
        $query->AndWhere('a.zone = :zone')
        ->setParameter('zone', $zone);
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getZone())
        {
            $query->AndWhere('a.zone = :zone')
            ->setParameter('zone', $recherche->getZone()->getId());
        }
        if($recherche->getCellule())
        {
            $query->AndWhere('a.cellule = :cellule')
            ->setParameter('cellule', $recherche->getCellule()->getId());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
    
    public function waiting(Recherche $recherche)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', false );
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getZone())
        {
            $query->AndWhere('a.zone = :zone')
            ->setParameter('zone', $recherche->getZone()->getId());
        }
        if($recherche->getCellule())
        {
            $query->AndWhere('a.cellule = :cellule')
            ->setParameter('cellule', $recherche->getCellule()->getId());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function waitingCellule(Recherche $recherche, $cellule)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', false )
        ->AndWhere('a.cellule = :cellule')
        ->setParameter('cellule', $cellule);
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getZone())
        {
            $query->AndWhere('a.zone = :zone')
            ->setParameter('zone', $recherche->getZone()->getId());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function waitingZone(Recherche $recherche, $zone)
    {
        $query =$this->createQueryBuilder('a');
        $query->where('a.enabled = :enabled')
            ->setParameter('enabled', false )
        ->AndWhere('a.zone = :zone')
        ->setParameter('zone', $zone);
        
        if($recherche->getProfession())
        {
            $query->AndWhere('a.profession = :profession')
            ->setParameter('profession', $recherche->getProfession());
        }
        
        if($recherche->getSexe())
        {
            $query->AndWhere('a.sexe = :sexe')
            ->setParameter('sexe', $recherche->getSexe());
        }
        
        if($recherche->getBloodgroup())
        {
            $query->AndWhere('a.bloodgroup = :bloodgroup')
            ->setParameter('bloodgroup', $recherche->getBloodgroup());
        }

        if($recherche->getSpecialite())
        {
            $query->AndWhere('a.specialite = :specialite')
            ->setParameter('specialite', $recherche->getSpecialite());
        }
        if($recherche->getRegion())
        {
            $query->AndWhere('a.region = :region')
            ->setParameter('region', $recherche->getRegion()->getId());
        }
        if($recherche->getDepartement())
        {
            $query->AndWhere('a.departement = :departement')
            ->setParameter('departement', $recherche->getDepartement());
        }
        if($recherche->getVille())
        {
            $query->AndWhere('a.ville = :ville')
            ->setParameter('ville', $recherche->getVille());
        }
        if($recherche->getEtablissement())
        {
            $query->AndWhere('a.etablissement = :etablissement')
            ->setParameter('etablissement', $recherche->getEtablissement());
        }
        if($recherche->getAnnee())
        {
            $query->AndWhere('a.annee = :annee')
            ->setParameter('annee', $recherche->getAnnee());
        }
        if($recherche->getStatus())
        {
            $query->AndWhere('a.status = :status')
            ->setParameter('status', $recherche->getStatus());
        }
        if($recherche->getSituation())
        {
            $query->AndWhere('a.situation = :situation')
            ->setParameter('situation', $recherche->getSituation());
        }
        return $query->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
    

   
    public function admins($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.roles = :val')
            ->setParameter('val', ['ROLE_ADMIN'])
        ;
    }
        
    public function poste()
	{
		// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
		$query = $this ->createQueryBuilder('a') 
					->Where('a.enabled = :enable')
					->setParameter('enable', true)
                    ->orderBy('a.nom', 'ASC')					;
					return $query;
	}

    public function depenseCellule($cellule)
	{
		// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
		$query = $this ->createQueryBuilder('a') 
					->Where('a.enabled = :enable')
					->setParameter('enable', true)
                    ->andWhere('a.cellule = :celulle')
					->setParameter('cellule', $cellule)
                    ->orderBy('a.nom', 'ASC')					;
					return $query;
	}
    
}
