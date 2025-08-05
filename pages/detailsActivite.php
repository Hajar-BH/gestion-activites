<?php
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }else{
        header('Location:index.php?page=liste_activites');
    }
?>

<?php
    $stmt = $pdo->query("select * from activites where idActivite = ".$_GET['id']);
    $stmt->execute();
    $activite = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main class="details_activite">
    <div class="container">
        <div class="header_details_activite">
            <h2>Détails de l'activité avec l'ID : <?php echo $_GET['id']?></h2>
            <a href="?page=liste_activites" class="boutton_liste_activites">Liste des activités</a>
        </div>

        <div class="diviseur">
            <div class="col_map">
                <div class="map" id="map"></div>
            </div>
            <div class="col_infos">
                <p><span>Type de l'activité : </span><?php echo $activite['typeActivite']?></p>
                <p><span>Email de l'organisateur : </span><?php echo $activite['mailOrganisateur']?></p>
                <p><span>Commune : </span><?php echo $activite['commune']?></p>
                <p><span>Description : </span><?php echo $activite['descriptif']?></p>
            </div>
        </div>
    </div>
</main>

<script> // Javascript pour Leaflet
    // les cordonnées sont récupérées depuis le tableau $activite | si elle n'existent pas sera par defaut de MPL
    var latitude = <?php echo $activite['latitude'] ?? 43.610769; ?>;
    var longitude = <?php echo $activite['longitude'] ?? 3.876716; ?>;

    var map = L.map('map').setView([latitude, longitude], 13);

    // un fond de carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // ici c'est pour un marqueur non déplaçable sur les coordonnées spécifiées
    var marker = L.marker([latitude, longitude], { draggable: false }).addTo(map)
    .bindPopup("Commune : <?php echo $activite['commune']?>")
    .openPopup();
</script>