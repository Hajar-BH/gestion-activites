<?php require_once('./fonctions/connexionBD.php') ?>
<?php require_once('./template/header.php') ?>

<?php
    if(isset($_GET['page'])){
        if($_GET['page'] == 'acceuil'){
            require_once('./pages/accueil.php');
        }else if($_GET['page'] == 'liste_activites'){ #dispatch 
            require_once('./pages/listActivites.php');
        }else if ($_GET['page'] == 'ajouter_activite'){
            require_once('./pages/ajouterActivite.php');
        }else if ($_GET['page'] == 'inscription'){
            require_once('./pages/ajouterUtilisateur.php');
        }else if ($_GET['page'] == 'ajouter_activite'){
            require_once('./pages/ajouterActivites.php');
        }else if ($_GET['page'] == 'liste_utilisateurs'){
            require_once('./pages/listUtilisateurs.php');
        }else if ($_GET['page'] == 'ajouter_type_activite'){
            require_once('./pages/ajouterTypeActivite.php');
        }else if ($_GET['page'] == 'activite_details'){
            require_once('./pages/detailsActivite.php');
        }else{
            require_once('./pages/404.php'); 
        }
    }else{
        require_once('./pages/accueil.php'); //par defaut sera l'accueil si l'utilisateur ne precise pas la clé et la valeur de GET
    } 
?>
<?php require_once('./template/footer.php') ?>