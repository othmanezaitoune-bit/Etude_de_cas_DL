<?php
// We assume 'config_pdo.php' handles the PDO connection setup ($PDO object).
require_once 'config_pdo.php';

// --- Data to Search For ---
// Nous allons chercher le nombre de voitures pour cette marque/modèle.
$brand_to_count = 'Mercedes';

try {
    // SQL Query: Compter le nombre de voitures appartenant à un modèle ou une marque spécifique.
    // 1. Jointure Voiture et Modele.
    // 2. Filtrage sur le nom du modèle (M.modele) utilisant LIKE.
    // 3. Utilisation de COUNT(V.immat) pour obtenir le total.
    $query = "
        SELECT 
            COUNT(V.immat) AS total_voitures_marque
        FROM 
            voiture V
        JOIN
            modele M ON V.id_modele = M.id_modele
        WHERE 
            M.modele LIKE :marque_recherche;
    ";

    // 1. Prepare the SQL statement
    $stmt = $PDO->prepare($query);
    
    // 2. Bind the user input safely (utilisation des jokers % pour LIKE)
    $search_param = '%' . $brand_to_count . '%';
    $stmt->bindParam(':marque_recherche', $search_param);

    // 3. Execute the statement
    $stmt->execute();
    
    // 4. Fetch the single result (the count)
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $car_count = $result['total_voitures_marque'] ?? 0;

    echo "<h2>Question 8: Nombre de voitures pour la marque : " . htmlspecialchars($brand_to_count) . "</h2>";
    
    // 5. Display the result
    echo "<p>Total de véhicules trouvés: <strong>" . htmlspecialchars($car_count) . "</strong></p>";

} catch(PDOException $e) {
    echo "Erreur lors de l'exécution de la requête: " . $e->getMessage();
}
?>