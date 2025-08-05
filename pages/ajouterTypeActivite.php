<?php
// ici la soumission du formulaire
    if($_POST != null){
        $categorie_generique = $_POST['categorie_generique'];
        $typeActivite = $_POST['type_activite'];

        if(!empty($categorie_generique) && !empty($typeActivite)){
            $sql = "INSERT INTO typesactivite(typeActivite, activiteGenerique) VALUES (:typeActivite, :categorieGenerique)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':typeActivite', $typeActivite);
            $stmt->bindParam(':categorieGenerique', $categorie_generique);

            if($stmt->execute()){
                header("Location:index.php?page=ajouter_activite&message=success");
                #Dans cette redirection on ajoute un parametre GET sous forme de clé-valeur, 
                #et ce message qu'on va recuperer par if (isset ($GET  
            }else{
                header("Location:index.php?page=ajouter_activite&message=error");
            }
        }else{
            echo "tout les champs sont obligatoire";
        }
    }
?>

<main class="ajouter_type_activite">
    <div class="container">
        <div class="header_ajouter_type_activite">
            <h2>Ajouter un nouveau type d'activité</h2>
            <a href="?page=ajouter_activite" class="boutton_ajouter_activite">Ajouter Une Activité</a>
        </div>
        <div class="form form_ajouter_type_activite">
            <form action="index.php?page=ajouter_type_activite" method="post">
                <div class="form_input_label">
                    <label for="categorieGenerique">Catégorie Générique :</label>
                    <input type="text" id="categorieGenerique" name="categorie_generique" value="<?php echo $_POST['categorie_generique'] ?? '' ?>"/>
                </div>

                <div class="form_input_label">
                    <label for="typeActivite">Type d'Activité :</label>
                    <input type="text" id="typeActivite" name="type_activite" value="<?php echo $_POST['type_activite'] ?? '' ?>">
                </div>

                <input class="btn_ajouter_type_activite" type="submit" value="ajouter" name="submit">
            </form>
        </div>
    </div>
</main>
