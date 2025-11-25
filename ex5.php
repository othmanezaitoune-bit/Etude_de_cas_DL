<?php

require_once 'config_pdo.php';


function fetch_all_models($PDO) {
    try {
        $stmt = $PDO->query("SELECT id_modele, modele FROM modele ORDER BY modele");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {

        return [];
    }
}

$all_models = fetch_all_models($PDO);
$success_message = '';
$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $codepostal = trim($_POST['codepostal'] ?? '');
    $immat = trim($_POST['immat'] ?? '');
    $couleur = trim($_POST['couleur'] ?? '');
    $datevoiture = trim($_POST['datevoiture'] ?? '');
    $id_modele = trim($_POST['id_modele'] ?? '');
    $datecarte = date('Y-m-d'); 


    if (empty($nom) || empty($prenom) || empty($immat) || empty($id_modele)) {
        $error_message = "Erreur: Les champs Nom, Prénom, Immatriculation et Modèle sont obligatoires.";
    } else {
        try {
            
            $PDO->beginTransaction();

            
            $query_prop = "INSERT INTO proprietaire (nom, prenom, adresse, ville, codepostal) 
                           VALUES (:nom, :prenom, :adresse, :ville, :codepostal)";
            $stmt_prop = $PDO->prepare($query_prop);
            $stmt_prop->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':codepostal' => $codepostal
            ]);

            
            $id_pers = $PDO->lastInsertId();

            
            $query_voit = "INSERT INTO voiture (immat, couleur, datevoiture, id_modele) 
                           VALUES (:immat, :couleur, :datevoiture, :id_modele)";
            $stmt_voit = $PDO->prepare($query_voit);
            $stmt_voit->execute([
                ':immat' => $immat,
                ':couleur' => $couleur,
                ':datevoiture' => $datevoiture,
                ':id_modele' => $id_modele
            ]);

            
            $query_cg = "INSERT INTO cartegrise (id_pers, immat, datecarte) 
                         VALUES (:id_pers, :immat, :datecarte)";
            $stmt_cg = $PDO->prepare($query_cg);
            $stmt_cg->execute([
                ':id_pers' => $id_pers,
                ':immat' => $immat,
                ':datecarte' => $datecarte
            ]);

            
            $PDO->commit();
            $success_message = "Insertion réussie : Le propriétaire ($nom $prenom) et la voiture ($immat) ont été ajoutés, ainsi que l'enregistrement de la carte grise.";

        } catch (PDOException $e) {
            
            $PDO->rollBack();
            $error_message = "Erreur lors de l'insertion. Vérifiez que l'immatriculation est unique et que le modèle existe : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Question 10 - Insertion Multiple</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        form { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        fieldset { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        legend { font-weight: bold; color: #555; padding: 0 10px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="date"], select { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .message.success { color: green; font-weight: bold; }
        .message.error { color: red; font-weight: bold; }
        .input-group { position: relative; }
        #model-list {
            position: absolute;
            z-index: 10;
            background-color: white;
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
            display: none;
        }
        #model-list div {
            padding: 8px;
            cursor: pointer;
        }
        #model-list div:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<h1>Question 10 (Insertion)</h1>

<?php if ($success_message): ?>
    <p class="message success"><?php echo $success_message; ?></p>
<?php endif; ?>
<?php if ($error_message): ?>
    <p class="message error"><?php echo $error_message; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <fieldset>
        <legend>Informations du Propriétaire</legend>
        <label for="nom">Nom (*)</label>
        <input type="text" id="nom" name="nom" required>
        
        <label for="prenom">Prénom (*)</label>
        <input type="text" id="prenom" name="prenom" required>
        
        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse">
        
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville">
        
        <label for="codepostal">Code Postal</label>
        <input type="text" id="codepostal" name="codepostal">
    </fieldset>

    <fieldset>
        <legend>Informations du Véhicule (Nouvelle Carte Grise)</legend>
        
        <label for="immat">Immatriculation (*)</label>
        <input type="text" id="immat" name="immat" placeholder="Ex: XY-456-ZA" required>
        
        <label for="couleur">Couleur</label>
        <input type="text" id="couleur" name="couleur">
        
        <label for="datevoiture">Date de Mise en Circulation</label>
        <input type="date" id="datevoiture" name="datevoiture">
        
        
        <label for="modele_saisie">Marque / Modèle (*)</label>
        <div class="input-group">
            <input type="text" id="modele_saisie" placeholder="Commencez à taper le modèle (ex: Merc)" autocomplete="off" required>
        
            <div id="model-list"></div>
        </div>
        
        
        <input type="hidden" id="id_modele" name="id_modele" required>
    </fieldset>

    <button type="submit">Insérer Propriétaire & Voiture</button>
</form>

<script>
    
    const allModels = <?php echo json_encode($all_models); ?>;
    
    const inputModeleSaisie = document.getElementById('modele_saisie');
    const inputIdModele = document.getElementById('id_modele');
    const modelListDiv = document.getElementById('model-list');

    
    inputModeleSaisie.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        
    
        modelListDiv.innerHTML = '';
        
        if (searchText.length < 2) {
            modelListDiv.style.display = 'none';
            return;
        }

    
        const filteredModels = allModels.filter(model => 
            model.modele.toLowerCase().includes(searchText)
        );

        if (filteredModels.length > 0) {
            filteredModels.forEach(model => {
                const item = document.createElement('div');
                item.textContent = model.modele;
                item.setAttribute('data-id', model.id_modele);
                
    
                item.addEventListener('click', function() {
    
                    inputModeleSaisie.value = this.textContent;
    
                    inputIdModele.value = this.getAttribute('data-id');
    
                    modelListDiv.style.display = 'none';
                });
                
                modelListDiv.appendChild(item);
            });
            modelListDiv.style.display = 'block';
        } else {
            modelListDiv.style.display = 'none';
        }
    });

    
    document.addEventListener('click', function(event) {
        if (!inputModeleSaisie.contains(event.target) && !modelListDiv.contains(event.target)) {
            modelListDiv.style.display = 'none';
        }
    });
    
    
    document.querySelector('form').addEventListener('submit', function(e) {
        if (inputIdModele.value === '') {
            if (inputModeleSaisie.value.length > 0) {
                 alert('Veuillez sélectionner un modèle valide dans la liste déroulante.');
            }
    
            if(inputModeleSaisie.value.length > 0) {
                e.preventDefault();
            }
        }
    });

</script>

</body>
</html>