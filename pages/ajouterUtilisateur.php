<?php
    if($_POST != null){
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];

        if(empty($email) || empty($nom) || empty($prenom) || empty($telephone)){
          $error[] = "Tous les champs sont obligatoires.";
        }else {
          $sql_exists_email = "select * from utilisateurs where Adressemail = '$email'"; 
          $sth = $pdo->prepare($sql_exists_email);
          $sth->execute(); 
          $result = $sth->fetchAll();
          if($result != null){
              $error[] = "L'email existe déja  dans la base de données.";
          
          }else
            if(!empty($email) && !empty($nom) && !empty($prenom) && !empty($telephone)){
              $sql = "INSERT INTO utilisateurs(Adressemail, nom, prenom, telephone) VALUES (:Adressemail, :nom, :prenom, :telephone)";
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':Adressemail', $email); #eviter l'injection 
              $stmt->bindParam(':nom', $nom);
              $stmt->bindParam(':prenom', $prenom);
              $stmt->bindParam(':telephone', $telephone);
  
              if($stmt->execute()){
                  header("Location:index.php?page=liste_utilisateurs&message=success");
              }else{
                  header("Location:index.php?page=liste_utilisateurs&message=error");
              }
          }

        }
    }
?>
<?php
// ici on affiche les erreurs s'il y en a
if ($_POST != null && !empty($error)) {
    ?>
    <div class="container">
        <div class="message_alert error">
          <?php echo $error[0];?>
        </div>
    </div>
    <?php
}
?>

<main class="inscription">
    <div class="container">
        <div class="header_inscription">
            <h2 class="titre_main">Inscription.</h2>
            <a class= "liste_utilisateurs" href="?page=liste_utilisateurs">Liste des utilisateurs</a>
        </div>

        <div class="form_inscription">
            <form action="" method="post">
                <div class="form_input">
                    <label for="nom">Nom : </label>
                    <input type="text" name="nom" id="nom" value=<?php echo isset($_POST['nom']) ? $_POST['nom'] : '' ?> > <!--encas d'erreur le autres champs persistent -->
                </div>
                <div class="form_input">
                    <label for="prenom">Prénom : </label>
                    <input type="text" name="prenom" id="prenom" value=<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : '' ?> >
                </div>
                <div class="form_input">
                    <label for="email">Adresse Email : </label>
                    <input type="email" name="email" id="email"  value=<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?> >
                </div>
                <div class="form_input">
                    <label for="telephone">Téléphone : </label>
                    <input type="text" name="telephone" id="telephone"  value=<?php echo isset($_POST['telephone']) ? $_POST['telephone'] : '' ?> >
                </div>
                <input class="btn_submit" name="submit" type="submit" value="S'enregistrer"/>
            </form>
        </div>
    </div>
</main>