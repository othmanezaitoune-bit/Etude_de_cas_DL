<?php
require_once 'config_pdo.php';

try {
    // 1. Fetch ALL actual IDs that exist in the table (removing the LIMIT 5)
    $query_select = "SELECT id_modele FROM modele"; 
    $stmt_select = $PDO->query($query_select);
    $existing_ids = $stmt_select->fetchAll(PDO::FETCH_COLUMN);

    // 2. Define the new data for ALL 6 models (price, color, purchase price)
    // I've added a 6th line of sample data for the BMW Série 3 / 83321TY455
    $nouvelles_donnees = [
        [45000.00, 'Noir', 38000.00],   // For 178524ER45 (Mercedes GLA)
        [35000.00, 'Blanc', 29000.00],  // For 17C92853AZ (Mercedes C-Class)
        [55000.00, 'Rouge', 45000.00],  // For 33356677PO (Golf GTI)
        [42000.00, 'Bleu', 35000.00],   // For 563339GH56 (Mercedes A-Class)
        [38000.00, 'Gris', 32000.00],   // For 7499RF5679 (BMW X5)
        [40000.00, 'Rouge', 34000.00]   // <-- NEW DATA for 83321TY455 (BMW Série 3)
    ];
    
    // Check if the counts match before proceeding
    if (count($existing_ids) !== count($nouvelles_donnees)) {
        throw new Exception("Mismatch between the number of existing IDs and new data rows. Found " . count($existing_ids) . " IDs but only " . count($nouvelles_donnees) . " data rows.");
    }
    
    // 3. Prepare the update statement
    $query_update = "UPDATE modele 
                     SET prix = ?, couleur = ?, prixachat = ?
                     WHERE id_modele = ?";
              
    $stmt_update = $PDO->prepare($query_update);
    
    $count = 0;
    // 4. Loop through all 6 records to execute the update
    for ($i = 0; $i < count($existing_ids); $i++) {
        $data_to_set = $nouvelles_donnees[$i]; 
        $id_to_match = $existing_ids[$i]; 
        
        $stmt_update->execute([
            $data_to_set[0], 
            $data_to_set[1], 
            $data_to_set[2], 
            $id_to_match
        ]);
        $count += $stmt_update->rowCount();
    }
    
    echo "$count modèles mis à jour avec succès!";
    
} catch(PDOException $e) {
    echo "Erreur PDO: " . $e->getMessage();
} catch(Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>