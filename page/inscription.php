<?php
     $bdd = new PDO ('mysql:host=localhost;dbname=school', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    if(isset($_POST['valider'])){
               $nom = htmlspecialchars($_POST['nom']);
               $prenom = htmlspecialchars($_POST['prenom']);
               $date = htmlspecialchars($_POST['date']);
               $email = htmlspecialchars($_POST['email']);
               $password = sha1($_POST['password']);
               $confpassword = sha1($_POST['confpassword']);

        if(!empty($nom) AND !empty($prenom) AND !empty($date) AND !empty($email) AND !empty($password) AND !empty($confpassword)){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $reqmail = $bdd->prepare("SELECT * FROM membres WHERE email=?");
                $reqmail ->execute(array($email));
                $mailExist = $reqmail -> rowCount();
                if($mailExist==0){
                    if($password == $confpassword){
                        $req = $bdd->prepare("INSERT INTO membres(nom_membre,prenom_membre,date_naissance,email,mot_passe) VALUES(?,?,?,?,?)");
                        $req -> execute(array($nom,$prenom,$date,$email,$password));
                        $erreur = 'Votre compte a bien été créé <a href=\"connexion.php\">Me connecter</a>';
                    }else{
                        $erreur = 'Les mots de passe ne sont pas identiques !';
                    }
                }else{
                    $erreur = 'Adresse email déjà utilisé !';
                }
            }else{
                $erreur = 'Votre adresse mail n\'est pas valide !';
            }

        }else{
            $erreur = 'Tous les champs doivent être complétés !';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>GS-Inscription</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../css/style.css"/>
        <link rel="icon" type="image/png" href="../img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/ionicons.css">
        <style>
            .identification .button{
                display: inline-block;
                vertical-align: middle;
                width: 130px;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div class="connexion_block">
            <form class="identification" method="POST" action="#">
                <h1 class="titre">GS-Inscription</h1>
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" placeholder="Entrer votre nom" value="<?php if(isset($nom)){ echo $nom;} ?>"/>

                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" placeholder="Entrer votre prénom" value="<?php if(isset($prenom)){ echo $prenom;} ?>"/>

                <label for="date">Date de naissance : </label>
                <input type="date" name="date" id="date" value="<?php if(isset($date)){ echo $date;} ?>"/>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Entrer votre Email" value="<?php if(isset($email)){ echo $email;} ?>"/>
                
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Entrer le mot de passe"/>

                <label for="confpassword">Confirmation du mot de passe:</label>
                <input type="password" id="confpassword" name="confpassword" placeholder="Entrer le mot de passe"/>

                <input class="button" type="submit" name="valider" value="S'inscrire"/>
                <input class="button" type="reset" name="annuler" value="Annuler"/>
            </form>
        <?php 
            if(isset($erreur)){
        ?>
                <p style="color:red;"><?php echo $erreur;?></p>
        <?php
            }
        ?>
        </div>
        <?php include("footer.php"); ?>
    </body>
</html>