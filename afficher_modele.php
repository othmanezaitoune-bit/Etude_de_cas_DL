<?php
require_once 'config_pdo.php';

try {
    // 1. The SELECT query to retrieve all models, ordered by the 'modele' column (brand/make)
    $query = "SELECT id_modele, modele, carburant, prix, couleur, prixachat 
              FROM modele 
              ORDER BY modele";

    // 2. Use $PDO->query() for SELECT statements
    $stmt = $PDO->query($query);
    
    // 3. Fetch all results as an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Contenu de la table Modele (Trié par Marque)</h2>";
    
    // 4. Display the results in an HTML table for readability
    if (count($results) > 0) {
        // Start the table
        echo "<table border='1'>";
        
        // Display header row (using the column names)
        echo "<tr>";
        foreach (array_keys($results[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";

        // Display data rows
        foreach ($results as $row) {
            echo "<tr>";
            foreach ($row as $data) {
                echo "<td>" . htmlspecialchars($data) . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Aucun modèle trouvé dans la base de données.</p>";
    }

} catch(PDOException $e){
    // Catch and display any database connection or query errors
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>