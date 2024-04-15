<?php
session_start();
$bdd = new PDO ('mysql:host=localhost;dbname=school', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_GET['id']) AND $_GET['id']>0){
    $getID = intval($_GET['id']);
    $requete = $bdd -> prepare("SELECT id_membre, nom_membre, prenom_membre, pays, sexe, profession, email, avatar, 
                                        DATE_FORMAT(date_naissance, '%d/%m/%Y') AS date_naiss 
                                FROM membres WHERE id_membre = ?");
    $requete -> execute(array($getID));
    $donnees = $requete -> fetch();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mon profil</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../css/style.css"/>
        <link rel="icon" type="image/png" href="../img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/ionicons.css">
        <style>
        .user_infos{
            width: 500px;
            display: inline-block;
            vertical-align: top;
        }
        .user_photo{
            width: 400px;
            border-left: 4px solid black;
            display: inline-block;
            vertical-align: top;
        }
        .user_photo figure{
            width: 200px;
            height: 250px;
            margin-left: 80px;
            border: 2px solid black;
        }
        .user_photo img{
            height: 100%;
            margin: auto;
        }
        .user_photo figcaption{
            font-size: 25px;
            font-weight: bold;
            margin-left: 20px;
        }
        </style>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div class="page_block">
            <h1>Profil du Membre: <?php echo $donnees['nom_membre'].' '.$donnees['prenom_membre'];  ?></h1>
            <br/><br/>
            <div class="user_infos">
                <table>
                    <tr>
                        <td>Nom:</td>
                        <td><?php echo $donnees['nom_membre'];?></td>
                    </tr>
                    <tr>
                        <td>Prénom:</td>
                        <td><?php echo $donnees['prenom_membre'];?></td>
                    </tr>
                    <tr>
                        <td>Date de naissance: </td>
                        <td><?php echo $donnees['date_naiss'];?></td>
                    </tr>
                    <tr>
                        <td>Pays:</td>
                        <td><?php echo $donnees['pays'];?></td>
                    </tr>
                    <tr>
                        <td>Sexe:</td>
                        <td><?php echo $donnees['sexe'];?></td>
                    </tr>
                    <tr>
                        <td>Profession:</td>
                        <td><?php echo $donnees['profession'];?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $donnees['email'];?></td>
                    </tr>
                </table>
            </div><div class="user_photo">
                <figure>
                <?php
                    if(!empty($donnees['avatar'])){
                ?>
                    <a href="../img/membres/avatars/<?php echo $donnees['avatar'] ?>">
                        <img src="../img/membres/avatars/<?php echo $donnees['avatar'] ?>" alt="photo de profil"/>
                    </a>
                <?php    
                    }else{
                ?>
                <img src="../img/profil.jpg" alt="photo de profil"/>
                <?php
                }
                ?>
                    <figcaption>Photo de profil</figcaption>
                </figure>
            </div>
            <?php
                if(isset($_SESSION['id']) AND ($donnees['id_membre']==$_SESSION['id'])){
            ?>
            <a href="editionprofil.php">Editer mon profil</a>
            <?php for($i=0;$i<3;$i++){
            ?>
                &nbsp;
            <?php
            } 
            ?>
            <a href="deconnexion.php">Se déconnecter</a>
            <?php
            }
            ?>
        </div>
        <?php include("footer.php"); ?>
    </body>
</html>
<?php
}
?>