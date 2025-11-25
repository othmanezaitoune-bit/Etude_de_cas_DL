<?php
require_once 'config_pdo.php';

try {
    
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

    
    $stmt = $PDO->query($query);
    
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Question 9: Nombre de voitures pour chaque marque/modèle</h2>";
    
    
    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
    
        echo "<tr>";
        echo "<th>Marque / Modèle</th>";
        echo "<th>Nombre de Voitures</th>";
        echo "</tr>";

    
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