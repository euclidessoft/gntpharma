
<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=gntpharma', 'root', '');
//    $date = new \DateTime();
    $sth = $dbh->prepare( "SELECT * FROM promotion WHERE fin='".date('Y-m-d')."'");
    $sth->execute();
    $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultat as $promotion){
        $st = $dbh->prepare( "SELECT * FROM promotion_produit WHERE promotion_id=".$promotion['id']);
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        echo $promotion['id']."<br>";
       foreach ($result as $promotionproduit){

            $s = $dbh->prepare( "UPDATE produit SET promotion_id = NULL WHERE id=".$promotionproduit['produit_id']);
            $s->execute();
                echo "update successful";

        }
    }


} catch (PDOException $e) {
    // tenter de réessayer la connexion après un certain délai, par exemple
echo "echec de connexion";
   // DATABASE_URL = "mysql://gntpharma_user:Izat95^0@127.0.0.1:3306/gntpharma_database"
    }
/*
 * margin: 0 15px;
    padding: 25px 0;
    font-size: 18px;
    text-transform: uppercase;
    outline: none;
    color: var(--blanc);
    font-weight: 600;
    transition: all 200ms ease-in-out;
    border-bottom: 5px solid transparent;
}
 */
 ?>