<?php 
  $pdo = new PDO("mysql:host=localhost;dbname=monprojet", "root", "");

  if($_SERVER['REQUEST_METHOD'] === 'GET'){ # (GET demande)
    $sql = "SELECT DISTINCT activiteGenerique FROM typesActivite";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(); 
    $activiteGeneriques = $stmt->fetchAll(); 
    
    foreach($activiteGeneriques as $activiteGenerique) { 
        echo '<option value="' .$activiteGenerique['activiteGenerique'] . '">' . $activiteGenerique['activiteGenerique'] . '</option>';
    } 
  }
 
  if($_SERVER['REQUEST_METHOD'] === 'POST'){ # POST cad ENVOYER des données
    $sql = "SELECT typeActivite FROM typesActivite where activiteGenerique = '".$_POST['categorie']."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $types_activite = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($types_activite as $type_activite) {
        echo '<option value="' .$type_activite['typeActivite'] . '">' . $type_activite['typeActivite'] . '</option>';
    }
  }
