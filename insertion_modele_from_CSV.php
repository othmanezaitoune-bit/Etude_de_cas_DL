<?php
require_once 'config_pdo.php';

$fileName = "modele.csv";
$data = [];

// Lire le fichier CSV
$file = fopen($fileName, "r");
while($row = fgetcsv($file)) {
    $data[] = $row;
}
fclose($file);

// Récupérer les IDs existants
$query = "SELECT id_modele FROM modele";
$stmt = $PDO->query($query);
$existing_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Insérer les données
foreach($data as $row) {
    $id_modele = $row[0];
    $modele = $row[1];
    $carburant = $row[2];
    
    if(!in_array($id_modele, $existing_ids)) {
        $query = "INSERT INTO modele VALUES (?, ?, ?)";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$id_modele, $modele, $carburant]);
    }
}

echo "Data imported successfully";
?>