
<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
} catch (PDOException $e) {
    // tenter de réessayer la connexion après un certain délai, par exemple
}
