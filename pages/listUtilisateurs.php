<?php
    if(isset($_GET['message']) && $_GET['message'] ==  'success'){
        ?>
          <div class="container">
            <div class="message_alert success">
              <h3>L'utilisateur a été ajouté avec succès.</h3>
            </div>
          </div>
        <?php
      }
    
      if(isset($_GET['message']) && $_GET['message'] ==  'error'){
        ?>
          <div class="container">
            <div class="message_alert error">
              <h3>Une erreur s'est produite lors de la création de l'utilisateur.</h3>
            </div>
          </div>
        <?php
      }
?>

<main class="liste_utilisateurs">
    <div class="container">
        <div class="header_liste_utilisateurs">
            <h2>Liste Utilisateurs</h2>
            <a href="?page=inscription" class="boutton_ajouter_utilisateur">Ajouter un utilisateur</a>
        </div>

        <div class="table">
            <table border=1>
                <thead> 
                    <tr>
                        <th>Email</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM utilisateurs;";      
                        $sth = $pdo->prepare($sql);
                        $sth->execute(); 
                        $result = $sth->fetchAll(); 
                
                        foreach ($result as $utilisateur) { // Chaque élément de $result est un tableau assiciatif //
                            echo "<tr>";
                                echo "<td>".$utilisateur['Adressemail']."</td>"; 
                                echo "<td>".$utilisateur['nom']."</td>"; // on utilise la clé pour acceder a une valeur précise dans le tableau associatif
                                echo "<td>".$utilisateur['prenom']."</td>"; 
                                echo "<td>".$utilisateur['telephone']."</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>