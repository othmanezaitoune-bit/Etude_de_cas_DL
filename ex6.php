<?php
// Inclure la configuration de la connexion à la base de données
require_once 'config_pdo.php';

$results = [];
$search_term = '';
$message = 'Entrez une marque ou un modèle de voiture à rechercher (ex: Picasso, Mercedes).';

// --- LOGIQUE DE TRAITEMENT DU FORMULAIRE (Recherche) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Récupération et validation du terme de recherche
    $search_term = trim($_POST['modele_recherche'] ?? '');

    if (!empty($search_term)) {
        try {
            // SQL Query: Sélectionner les propriétaires d'un modèle de voiture donné.
            // Joins requis : Proprietaire -> cartegrise -> Voiture -> Modele
            $query = "
                SELECT 
                    P.nom, 
                    P.prenom, 
                    M.modele,
                    V.immat  
                FROM 
                    proprietaire P
                JOIN 
                    cartegrise CG ON P.id_pers = CG.id_pers
                JOIN 
                    voiture V ON CG.immat = V.immat
                JOIN
                    modele M ON V.id_modele = M.id_modele
                WHERE 
                    -- La clause LIKE permet de rechercher la marque ou le modèle partout dans le nom.
                    M.modele LIKE :modele_recherche
                ORDER BY
                    P.nom, P.prenom;
            ";

            // 2. Préparer la requête
            $stmt = $PDO->prepare($query);
            
            // 3. Binder le paramètre de recherche avec des jokers (wildcards)
            $search_param = '%' . $search_term . '%';
            $stmt->bindParam(':modele_recherche', $search_param);

            // 4. Exécuter la requête
            $stmt->execute();
            
            // 5. Récupérer tous les résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                $message = "Résultats trouvés pour le modèle/marque : " . htmlspecialchars($search_term);
            } else {
                $message = "Aucun propriétaire trouvé pour le modèle/marque : " . htmlspecialchars($search_term);
            }

        } catch (PDOException $e) {
            $message = "Erreur lors de l'exécution de la requête de recherche : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez entrer un terme de recherche.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Question 11 - Formulaire de Recherche</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        form { max-width: 400px; margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        input[type="text"] { width: 70%; padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; }
    </style>
</head>
<body>

<h1>Question 11: Recherche de Propriétaires par Marque/Modèle</h1>

<!-- FORMULAIRE DE RECHERCHE -->
<form method="POST" action="">
    <label for="modele_recherche">Entrez la Marque ou le Modèle :</label>
    <input type="text" id="modele_recherche" name="modele_recherche" value="<?php echo htmlspecialchars($search_term); ?>" required>
    <button type="submit">Rechercher</button>
</form>

<!-- MESSAGE DE STATUT -->
<p><?php echo $message; ?></p>

<!-- AFFICHAGE DES RÉSULTATS -->
<?php if (count($results) > 0): ?>
    <h2>Résultats</h2>
    <table>
        <tr>
            <th>Nom du Propriétaire</th>
            <th>Prénom du Propriétaire</th>
            <th>Modèle de Voiture</th>
            <th>Immatriculation</th>
        </tr>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nom']); ?></td>
            <td><?php echo htmlspecialchars($row['prenom']); ?></td>
            <td><?php echo htmlspecialchars($row['modele']); ?></td>
            <td><?php echo htmlspecialchars($row['immat']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>