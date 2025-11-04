<?php
require_once 'config_pdo.php';

try {
    $query = "ALTER TABLE modele 
              ADD COLUMN prix DECIMAL(10,2),
              ADD COLUMN couleur VARCHAR(30),
              ADD COLUMN prixachat DECIMAL(10,2)";
    
    $PDO->exec($query);
    echo "Table modele modifiée avec succès! Colonnes ajoutées: prix, couleur, prixachat";
    
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>