<?php
    if(isset($_GET['message']) && $_GET['message'] ==  'success'){
        ?>
          <div class="container">
            <div class="message_alert success">
              <h3>Le type d'activité a été ajouté avec succès.</h3>
            </div>
          </div>
        <?php
      }
      if(isset($_GET['message']) && $_GET['message'] ==  'error'){
        ?>
          <div class="container">
            <div class="message_alert error">
              <h3>Une erreur est survenue lors de la création du type d'activité.</h3>
            </div>
          </div>
        <?php
      }
// Vérification des données envoyées via le formulaire | PHP traite les données //
    if($_POST != null){ 
        $mailOrganisateur = $_POST['mailOrganisateur'];
        $typeActivite = $_POST['type_activite'];
        $commune = $_POST['commune'];
        $descriptif = $_POST['descriptif'];
        $date = $_POST['date']; 
        $longitude = !empty ($_POST['longitude'])?  $_POST['longitude'] : 3.876716; 
        $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : 43.610769;  

        // ici on insere les données saisies dans le formulaire dans la BD
        if(!empty($mailOrganisateur) && !empty($typeActivite) && !empty($commune) && !empty($descriptif) && !empty($date)){
            $sql = "INSERT INTO activites(idActivite, mailOrganisateur, typeActivite, commune,descriptif, date, longitude, latitude) 
            VALUES (null, :mailOrganisateur, :typeActivite, :commune, :descriptif, :date, :longitude, :latitude)"; 
            $stmt = $pdo->prepare($sql);  
            $stmt->bindParam(':mailOrganisateur', $mailOrganisateur); 
            $stmt->bindParam(':typeActivite', $typeActivite);
            $stmt->bindParam(':commune', $commune);
            $stmt->bindParam(':descriptif', $descriptif);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':latitude', $latitude);

            if($stmt->execute()){ 
                header("Location:index.php?page=liste_activites&message=success"); 
                //redirection vers ListActivites.php ms avec un message de succes 
            }else{
                header("Location:index.php?page=liste_activites&message=error"); // Sinon, un msg d'erreur s'affiche 
            }
        }else{
            ?>
            <div class="container">
                <div class="message_alert error">
                <h3>Tous les champs sont obligatoires.</h3>
                </div>
            </div> 
            <?php
        }
    }
?>

<main class="ajouter_activite">
    <div class="container">
        <div class="header_ajouter_activite">
            <h2>Ajouter Une nouvelle activité</h2>
            <a href="?page=liste_activites" class="boutton_liste_activites">Liste des activités</a>
        </div>

        <div class="diviseur">
            <div class="col_map">
                <div class="map" id="map"></div>
            </div>
            <div class="col_infos">
                <div class="form"> 
                    <form action="" method="post"> 
                        <div class="form_input_label"> <!-- Catégorie générique -->
                            <label for="categorieGenerique">Catégorie Générique :</label> 
                            <select id="categorieGenerique" name="categorie_generique"> 
                                <option value="">-- Choisir une catégorie --</option>
                            </select>
                        </div>

                        <div class="form_input_label select"> <!-- ici seront ajoutés dynamiquement via AJAX-->
                            <label for="typeActivite">Type d'Activité :</label>
                            <select id="typeActivite" name="type_activite">
                                <option value="">-- Sélectionner un type d'activité --</option>
                            </select>
                            <a class="btn_ajouter_type_activite" href="?page=ajouter_type_activite">Ajouter un nouveau type d'activité</a>
                        </div>
                        <div class="form_input_label">  
                            <label for="organisateur">Email Organisateur</label> 
                            <select name="mailOrganisateur" id="organisateur">
                                <option value="">Sélectionner l'organisteur</option>
                                <?php
                                $sql = "SELECT Adressemail FROM utilisateurs;";      
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(); 
                                $organisateurs = $stmt->fetchAll();
                                foreach ($organisateurs as $organisateur) { 
                                    echo "<option value=".$organisateur['Adressemail'].">".$organisateur['Adressemail']."</option>";
                                }  
                            ?>
                            </select>
                        </div>
                        <div class="form_input_label">  
                            <label for="commune">Commune</label>
                            <input type="text" id="commune" name="commune" placeholder="Entrez la commune où se déroulera l'activité"  value=<?php echo (isset($_POST['commune'])) ? $_POST['commune'] : '' ?>> 
                        </div>
                        <div class="form_input_label">
                            <label for="descriptif">Descriptif</label>
                            <textarea  id="descriptif" name="descriptif"><?php echo (isset($_POST['descriptif'])) ? $_POST['descriptif'] : '' ?></textarea>
                        </div>
                        <div class="form_input_label"> 
                            <label for="date">Date</label>
                            <input type="text" name="date" id="date" placeholder="aa-mm-jj"> 
                        </div> 
                        <input id="longitude" type="hidden" name="longitude" value="<?php echo $_POST['longitude'] ?? ''?>"> <!-- ces champs sont invisibles  -->
                        <input id="latitude" type="hidden" name="latitude" value="<?php echo $_POST['latitude'] ?? ''?>">
                        <input class="btn_ajouter_activite" type="submit" value="ajouter" name="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
 
<script> // Gestion dynamique avec AJAX - jQuery
    $(document).ready(function () {
        $.ajax({ 
            url: './fonctions/listeDeroulante.php', 
            type: 'GET', 
            success: function (response) { 
                $('#categorieGenerique').append(response); // #id categorieGenerique
            },
            error: function () {
                alert('Erreur lors de la récupération des catégories.');
            }
        });
         
        $('#categorieGenerique').change(function () { 
            var selectedCategory = $(this).val(); // this c'est l'element actuel categorieGenerique
            if (selectedCategory === '') return;

            $.ajax({
                url: './fonctions/listeDeroulante.php', 
                type: 'POST', 
                data: { categorie: selectedCategory }, //$_POST['category'] = selectedCategory
                success: function (response) {
                    $('#typeActivite').find('option').not(':first').remove();
                    $('#typeActivite').append(response); 
                },
                error: function () {
                    alert('Erreur lors de la récupération des types d\'activités.');
                }
            });
        });
    });
</script>
<!-- Leaflet -->
<script>
    var map = L.map('map').setView([43.610769, 3.876716], 13); // Montpellier par défaut avec un niveau de zoom de 13

    // Ajouter une couche de tuiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Placer un marqueur déplaçable que l'utilisateur peut déplacer
    var marker = L.marker([43.610769, 3.876716], { draggable: true }).addTo(map);
    marker.on('moveend', function (e) {
        var latlng = e.target.getLatLng();
        document.getElementById('latitude').value = latlng.lat; 
        document.getElementById('longitude').value = latlng.lng;
    });
</script>