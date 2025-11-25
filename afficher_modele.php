<?php
require_once 'config_pdo.php';

try {
    
    $query = "SELECT id_modele, modele, carburant, prix, couleur, prixachat 
              FROM modele 
              ORDER BY modele";

    
    $stmt = $PDO->query($query);
    
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Contenu de la table Modele (Trié par Marque)</h2>";
    
    
    if (count($results) > 0) {
    
        echo "<table border='1'>";
        
    
        echo "<tr>";
        foreach (array_keys($results[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";

    
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
    
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>