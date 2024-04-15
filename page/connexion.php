<?php
    session_start();
    
    $bdd = new PDO ('mysql:host=localhost;dbname=school', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    if(isset($_POST['valider'])){
        $emailconnect = htmlspecialchars($_POST['emailconnect']);
        $passwordconnect = sha1($_POST['passwordconnect']);
        if(!empty($emailconnect) AND !empty($passwordconnect)){
            if(filter_var($emailconnect, FILTER_VALIDATE_EMAIL)){    
                $requete = $bdd-> prepare("SELECT * FROM membres WHERE email=? AND mot_passe=?");
                $requete-> execute(array($emailconnect, $passwordconnect));
                $userexist = $requete -> rowCount();
                if($userexist==1){
                    $donnees = $requete -> fetch();
                    $_SESSION['id'] = $donnees['id_membre'];
                    $_SESSION['nom'] = $donnees['nom_membre'];
                    $_SESSION['prenom']= $donnees['prenom_membre'];
                    $_SESSION['date']= $donnees['date_naissance'];
                    $_SESSION['email'] = $donnees['email'];
                    header('Location: profil.php?id='.$_SESSION['id']);
                }else{
                    $erreur = 'Email ou Mot de passe incorrect!';
                }
            }else{
                    $erreur = 'Adresse email invalide !!';
            }
        }else{
            $erreur = 'Tous les champs doivent etre remplis';
        }
     }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>GS-Connexion</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../css/style.css"/>
        <link rel="icon" type="image/png" href="../img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/ionicons.css">
    </head>
    <body>
        <?php include("header.php"); ?>
        <div class="connexion_block">
            <form class="identification" method="POST" action="#">
                <h1 class="titre">GS-Connexion</h1>
                
                <label for="emailconnect">Email</label>
                <input type="email" id="emailconnect" name="emailconnect" placeholder="Entrer l'email"/>
                
                <label for="passwordconnect">Mot de passe</label>
                <input type="password" id="passwordconnect" name="passwordconnect" placeholder="Entrer le mot de passe"/>  

                <input class="button" type="submit" name="valider" value="Se connecter"/>
                <input class="button" type="reset" value="Annuler"/>
            </form>
        <?php 
            if(isset($erreur)){
                if($erreur=='Votre compte est connecté avec succès'){
        ?>
                    <p style="color:blue;"><?php echo $erreur;?></p>
        <?php
                }else{
        ?>
                    <p style="color:red;"><?php echo $erreur;?></p>
        <?php
                }
            }
        ?>
        </div>
        <?php include("footer.php"); ?>
    </body>
</html>