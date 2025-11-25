<?php

require_once 'config_pdo.php';

try {
   
    $query = "
        SELECT 
            V.immat, 
            V.couleur, 
            V.datevoiture,
            COUNT(CG.id_pers) AS nombre_de_proprietaires
        FROM 
            cartegrise CG
        JOIN 
            voiture V ON CG.immat = V.immat
        GROUP BY 
            V.immat, V.couleur, V.datevoiture
        HAVING 
            COUNT(CG.id_pers) > 1
        ORDER BY
            V.immat;
    ";

   
    $stmt = $PDO->query($query);
    
   
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Véhicules ayant plusieurs copropriétaires</h2>";
    
   
    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
   
        echo "<tr>";
        echo "<th>Immatriculation</th>";
        echo "<th>Couleur</th>";
        echo "<th>Date du Véhicule</th>";
        echo "<th>Nombre de Propriétaires</th>";
        echo "</tr>";

   
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['immat']) . "</td>";
            echo "<td>" . htmlspecialchars($row['couleur']) . "</td>";
            echo "<td>" . htmlspecialchars($row['datevoiture']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre_de_proprietaires']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Aucun véhicule trouvé avec plusieurs copropriétaires.</p>";
    }

} catch(PDOException $e) {
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>