<?php

require_once 'config_pdo.php';


$model_to_search = 'Mercedes GLA';//picasso

try {

    $query = "
        SELECT 
            P.nom, 
            P.prenom, 
            M.modele,
            V.immat  -- Optional: Include immat to show which specific car it is
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


    $stmt = $PDO->prepare($query);
    

    $search_param = '%' . $model_to_search . '%';
    $stmt->bindParam(':modele_recherche', $search_param);


    $stmt->execute();
    

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Propriétaires de véhicules de type : " . htmlspecialchars($model_to_search) . "</h2>";
    

    if (count($results) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        

        echo "<tr>";
        echo "<th>Nom du Propriétaire</th>";
        echo "<th>Prénom du Propriétaire</th>";
        echo "<th>Modèle de Voiture</th>";
        echo "<th>Immatriculation</th>";
        echo "</tr>";


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