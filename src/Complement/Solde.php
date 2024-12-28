<?php


namespace App\Complement;


use App\Entity\Ecriture;

class Solde
{
    public function montantcaisse($em, $compte){



        $credits = $em->getRepository(Ecriture::class)->findby(['comptecredit' => $compte, 'type' => 'Espece']);
        $debits = $em->getRepository(Ecriture::class)->findby(['comptedebit' => $compte, 'type' => 'Espece']);
        $caisse = 0;
        $debitcaisse = 0;
        foreach($credits as $ecriture) {
            $caisse = $caisse + $ecriture->getMontant();

        }
        foreach($debits as $ecriture) {
            $debitcaisse = $debitcaisse + $ecriture->getMontant();

        }

        $result = $caisse - $debitcaisse;
        return $result;

    }

    public function montantbanque($em, $compte){

        $credits = $em->getRepository(Ecriture::class)->findby(['comptecredit' => $compte, 'type' => 'Banque']);
        $debits = $em->getRepository(Ecriture::class)->findby(['comptedebit' => $compte, 'type' => 'Banque']);
        $caisse = 0;
        $debitcaisse = 0;
        foreach($credits as $ecriture) {
            $caisse = $caisse + $ecriture->getMontant();

        }
        foreach($debits as $ecriture) {
            $debitcaisse = $debitcaisse + $ecriture->getMontant();

        }
        $result = $caisse - $debitcaisse;
        return $result;

    }

}