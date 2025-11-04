<?php
require_once 'config_pdo.php';


$donnees = [
    ['17C92853AZ', 45000.00, 'Noir', 38000.00],
    ['18D12345BC', 35000.00, 'Blanc', 29000.00],
    ['19E67890DE', 55000.00, 'Rouge', 45000.00],
    ['20F54321FG', 42000.00, 'Bleu', 35000.00],
    ['21G98765HI', 38000.00, 'Gris', 32000.00]
];

try {
    $query = "UPDATE modele 
              SET prix = ?, couleur = ?, prixachat = ?
              WHERE id_modele = ?";
              
    $stmt = $PDO->prepare($query);
    
    $count = 0;
    foreach($donnees as $ligne) {
        $stmt->execute([$ligne[1], $ligne[2], $ligne[3], $ligne[0]]);
        $count += $stmt->rowCount();
    }
    
    echo "$count modèles mis à jour avec succès!";
    
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>