<?php
// Include the database connection configuration.
require_once 'config_pdo.php';

try {
    // SQL Query: Select vehicles (immat, couleur, datevoiture) that appear more than once 
    // in the 'cartegrise' table, indicating multiple owners.
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

    // 1. Execute the query
    $stmt = $PDO->query($query);
    
    // 2. Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Véhicules ayant plusieurs copropriétaires</h2>";
    
    // 3. Display the results in a simple HTML table
    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
        // Table Header
        echo "<tr>";
        echo "<th>Immatriculation</th>";
        echo "<th>Couleur</th>";
        echo "<th>Date du Véhicule</th>";
        echo "<th>Nombre de Propriétaires</th>";
        echo "</tr>";

        // Table Rows (Data)
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