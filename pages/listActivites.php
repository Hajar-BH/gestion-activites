<?php
if (isset($_GET['message']) && $_GET['message'] ==  'success') {
?>
  <div class="container">
    <div class="message_alert success">
      <h3>L'activité a été ajoutée avec succès.</h3>
    </div>
  </div>
<?php
}
if (isset($_GET['message']) && $_GET['message'] ==  'error') {
?>
  <div class="container">
    <div class="message_alert error">
      <h3>Une erreur est survenue lors de la création du type d'activité.</h3>
    </div>
  </div>
<?php
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
  header('Location: ?page=liste_activites');
}
$mode_recherche = false;
# Partie : Gestion de recherche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $mode_recherche = true; 
  $WHERE = "";
  $INFOS = "";
  foreach ($_POST as $nom => $valeur) { 
    if ($nom != "submit" &&  $valeur != "") { 
      if ($WHERE == "") $WHERE .= "WHERE "; 
      else {
        $WHERE .= " AND ";
      }
      #gestion des autres champs
      if ($nom == "date_debut") $WHERE .= "date >= '$valeur'"; 
      else if ($nom == "date_fin") $WHERE .= "date <= '$valeur'";
      else if ($nom == "typeActivite") $WHERE .= "typesactivite.typeActivite = '$valeur'"; 
      else $WHERE .= "$nom='$valeur'";
      $INFOS .= "$nom='$valeur' ";
    }
  }
  $sql = "SELECT activites.*, typesactivite.activiteGenerique 
                FROM activites 
                LEFT JOIN typesactivite ON activites.typeActivite = typesactivite.typeActivite $WHERE;"; # on relie les deux tables pour recupérer les informations manquantes comme par exemple sa description ou la commune ou se deroulera l'activité.
  $stmt = $pdo->prepare($sql); 
  $stmt->execute(); 
  $results = $stmt->fetchAll(); 
}else { 
  $sql = "SELECT * FROM activites;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $results = $stmt->fetchAll();
}
?>

<main class="liste_activites">
  <div class="container">
    <div class="header_liste_activites">
      <h2 class="titre_main">Liste des activités.</h2>
      <a class="boutton_ajouter_activite" href="?page=ajouter_activite">Ajouter une activité</a>
    </div>
    <div class="rechercher">
      <form action="index.php?page=liste_activites" method="POST">
        <div>
          <label for="organiateur">Recherche par organisateur</label>
          <select name="mailOrganisateur" id="organisateur">
            <option value="">Sélectionner l'organisteur</option>
            <?php
            $sql = "SELECT Adressemail FROM utilisateurs;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $organisateurs = $stmt->fetchAll(); #tableau associatif []

            foreach ($organisateurs as $organisateur) { 
              echo "<option value=" . $organisateur['Adressemail'] . ">" . $organisateur['Adressemail'] . "</option>";
            }
            ?>
          </select>
        </div>
        <div>
          <label for="categorieGenerique">Recherche par categorie générique</label>
          <select name="activiteGenerique" id="categorieGenerique">
            <option value="">Sélectionner une categorie</option>
          </select>
        </div>
        <div>
          <label for="type_activite">Recherche par type activité</label>
          <select name="typeActivite" id="typeActivite">
            <option value="">Sélectionner un type d'activité</option>
          </select>
        </div>
        <div>
          <label for="commune">Recherche par commune</label>
          <input type="text" name="commune" id="commune" placeholder="Entrez la commune où se déroulera l'activité" value="<?php echo $_POST['commune'] ?? '' ?>">
        </div>
        <div class="input_date">
          <label for="date">recherche par date</label>
          <input type="text" name="date_debut" placeholder="aa-mm-jj" value="<?php echo $_POST['date_debut'] ?? '' ?>">
          <input type="text" name="date_fin" placeholder="aa-mm-jj" value="<?php echo $_POST['date_fin'] ?? '' ?>">
        </div>
        <input type="submit" name="submit" value="soumettre" />
        <input type="submit" name="reset" value="Réinitialiser" />
      </form>
    </div>

    <?php
    if ($mode_recherche === true) {
    ?>
      <h4 class="mode_recherche">Resultat de votre recherche : </h4>
    <?php
    }
    ?>
    <?php
    if (count($results) == 0) {
    ?>
      <h4 class="resultat_vide">La table est vide</h4>
    <?php
    } else {
    ?>
      <div class="table">
        <table border=1>
          <thead> 
            <tr>
              <th>Email Organisateur</th>
              <th>Type D'activité</th>
              <th>Commune</th>
              <th>Descriptif</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            foreach ($results as $activite) { 
              echo "<tr>"; 
              echo "<td>" . $activite['mailOrganisateur'] . "</td>"; 
              echo "<td>" . $activite['typeActivite'] . "</td>"; 
              echo "<td>" . $activite['commune'] . "</td>"; 
              echo "<td>" . $activite['descriptif'] . "</td>";
              echo "<td>" . $activite['date'] . "</td>"; #on utilise la clé pour acceder a une valeur précise dans le tableau associatif
              echo "<td><a class='btn_details' href='?page=activite_details&id=" . $activite['idActivite'] . "'>details</a></td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php
    }
    ?>
  </div>
</main>

<script>
  $(document).ready(function() {
    $.ajax({
      url: './fonctions/listeDeroulante.php',
      type: 'GET',
      success: function(response) {
        $('#categorieGenerique').append(response); // #id categorieGenerique
      },
      error: function() {
        alert('Erreur lors de la récupération des catégories.');
      }
    });

    $('#categorieGenerique').change(function() {
      var selectedCategory = $(this).val(); // this c'est l'element actuel categorieGenerique
      if (selectedCategory === '') return; 

      $.ajax({
        url: './fonctions/listeDeroulante.php',
        type: 'POST',
        data: {
          categorie: selectedCategory //$_POST['category'] = selectedCategory
        },
        success: function(response) {
          $('#typeActivite').find('option').not(':first').remove();
          $('#typeActivite').append(response);
        },
        error: function() {
          alert('Erreur lors de la récupération des types d\'activités.');
        }
      });
    });
  });
</script>