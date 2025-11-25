<?php

require_once 'config_pdo.php';

$results = [];
$search_nom = '';
$message = 'Entrez le nom de famille (Nom) du propriétaire à rechercher (ex: Algout).';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $search_nom = trim($_POST['nom_recherche'] ?? '');

    if (!empty($search_nom)) {
        try {

            $query = "
                SELECT 
                    P.nom, 
                    P.prenom, 
                    V.immat, 
                    V.couleur, 
                    V.datevoiture,
                    M.modele  
                FROM 
                    proprietaire P
                JOIN 
                    cartegrise CG ON P.id_pers = CG.id_pers
                JOIN 
                    voiture V ON CG.immat = V.immat
                JOIN 
                    modele M ON V.id_modele = M.id_modele -- Ajout du modèle pour une meilleure information
                WHERE 
                    P.nom LIKE :nom_recherche
                ORDER BY
                    V.immat;
            ";


            $stmt = $PDO->prepare($query);
            

            $search_param = '%' . $search_nom . '%';
            $stmt->bindParam(':nom_recherche', $search_param);


            $stmt->execute();
            

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                $nom_complet = htmlspecialchars($results[0]['prenom'] . ' ' . $results[0]['nom']);
                $message = "Résultats trouvés pour le propriétaire : " . $nom_complet;
            } else {
                $message = "Aucun véhicule trouvé pour le propriétaire dont le nom contient : " . htmlspecialchars($search_nom);
            }

        } catch (PDOException $e) {
            $message = "Erreur lors de l'exécution de la requête de recherche : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez entrer un nom de famille à rechercher.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Question 12 - Recherche de Véhicules par Propriétaire</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { max-width: 400px; margin-bottom: 30px; padding: 20px; border: 1px solid #007bff; border-radius: 5px; background-color: #f9f9f9; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #007bff; }
        input[type="text"] { width: calc(100% - 100px); padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #1e7e34; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #e9ecef; color: #333; }
    </style>
</head>
<body>

<h1>Question 12: Recherche de Véhicules par Propriétaire</h1>


<form method="POST" action="">
    <label for="nom_recherche">Entrez le Nom de Famille (Nom) du Propriétaire :</label>
    <div style="display: flex; align-items: center;">
        <input type="text" id="nom_recherche" name="nom_recherche" value="<?php echo htmlspecialchars($search_nom); ?>" required>
        <button type="submit">Rechercher</button>
    </div>
</form>


<p><?php echo $message; ?></p>


<?php if (count($results) > 0): ?>
    <h2>Véhicules trouvés (<?php echo count($results); ?>)</h2>
    <table>
        <tr>
            <th>Propriétaire (Nom, Prénom)</th>
            <th>Modèle</th>
            <th>Immatriculation</th>
            <th>Couleur</th>
            <th>Date de Mise en Circulation</th>
        </tr>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nom']) . ', ' . htmlspecialchars($row['prenom']); ?></td>
            <td><?php echo htmlspecialchars($row['modele']); ?></td>
            <td><?php echo htmlspecialchars($row['immat']); ?></td>
            <td><?php echo htmlspecialchars($row['couleur']); ?></td>
            <td><?php echo htmlspecialchars($row['datevoiture']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>