<?php
// We assume 'config_pdo.php' handles the PDO connection setup ($PDO object).
require_once 'config_pdo.php';

try {
    // SQL Query: Compter le nombre de voitures pour chaque marque/modèle et l'afficher.
    // Cette requête utilise GROUP BY pour agréger le compte par modèle.
    $query = "
        SELECT 
            M.modele, 
            COUNT(V.immat) AS nombre_de_voitures
        FROM 
            voiture V
        JOIN
            modele M ON V.id_modele = M.id_modele
        GROUP BY 
            M.modele
        ORDER BY
            nombre_de_voitures DESC;
    ";

    // 1. Execute the query
    $stmt = $PDO->query($query);
    
    // 2. Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Question 9: Nombre de voitures pour chaque marque/modèle</h2>";
    
    // 3. Display the results in a simple HTML table
    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
        // Table Header
        echo "<tr>";
        echo "<th>Marque / Modèle</th>";
        echo "<th>Nombre de Voitures</th>";
        echo "</tr>";

        // Table Rows (Data)
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['modele']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre_de_voitures']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Aucun modèle de voiture trouvé dans la base de données.</p>";
    }

} catch(PDOException $e) {
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>