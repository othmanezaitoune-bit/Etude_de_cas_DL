<?php
// Include the database connection configuration.
require_once 'config_pdo.php';

// --- Data to Search For (As specified in the assignment) ---
$model_to_search = 'Picasso';

try {
    // SQL Query: Select the owner's name and the specific car model they own.
    // This requires joining: Proprietaire -> cartegrise -> Voiture -> Modele
    $query = "
        SELECT 
            P.nom, 
            P.prenom, 
            M.modele,
            V.immat  -- Vehicle registration number
        FROM 
            proprietaire P
        JOIN 
            cartegrise CG ON P.id_pers = CG.id_pers
        JOIN 
            voiture V ON CG.immat = V.immat
        JOIN
            modele M ON V.id_modele = M.id_modele
        WHERE 
            M.modele LIKE :modele_recherche
        ORDER BY
            P.nom, P.prenom;
    ";

    // 1. Prepare the SQL statement for security
    $stmt = $PDO->prepare($query);
    
    // 2. Bind the user input safely (using % wildcards for LIKE search)
    $search_param = '%' . $model_to_search . '%';
    $stmt->bindParam(':modele_recherche', $search_param);

    // 3. Execute the statement
    $stmt->execute();
    
    // 4. Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Propriétaires de véhicules de type : " . htmlspecialchars($model_to_search) . "</h2>";
    
    // 5. Display the results in a simple HTML table
    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
        // Table Header
        echo "<tr>";
        echo "<th>Nom du Propriétaire</th>";
        echo "<th>Prénom du Propriétaire</th>";
        echo "<th>Modèle de Voiture</th>";
        echo "<th>Immatriculation</th>";
        echo "</tr>";

        // Table Rows (Data)
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['modele']) . "</td>";
            echo "<td>" . htmlspecialchars($row['immat']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Aucun propriétaire trouvé pour le modèle : " . htmlspecialchars($model_to_search) . ".</p>";
    }

} catch(PDOException $e) {
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>