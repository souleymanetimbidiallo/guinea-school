<?php
session_start();
$bdd = new PDO ('mysql:host=localhost;dbname=school', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_SESSION['id'])){
    $requete = $bdd -> prepare("SELECT * FROM membres WHERE id_membre = ?");
    $requete -> execute(array($_SESSION['id']));
    $donnees = $requete -> fetch();
    if(isset($_POST['newNom']) AND !empty($_POST['newNom']) AND $_POST['newNom'] != $donnees['nom_membre']){
            $newNom = htmlspecialchars($_POST['newNom']);
            $reqNom = $bdd -> prepare("UPDATE membres SET nom_membre=? WHERE id_membre=?");
            $reqNom -> execute(array($newNom, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newPrenom']) AND !empty($_POST['newPrenom']) AND $_POST['newPrenom'] != $donnees['prenom_membre']){
            $newPrenom = htmlspecialchars($_POST['newPrenom']);
            $reqPrenom = $bdd -> prepare("UPDATE membres SET prenom_membre=? WHERE id_membre=?");
            $reqPrenom -> execute(array($newPrenom, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newDate']) AND !empty($_POST['newDate']) AND $_POST['newDate'] != $donnees['date_naissance']){
            $newDate = htmlspecialchars($_POST['newDate']);
            $reqDate = $bdd -> prepare("UPDATE membres SET date_naissance=? WHERE id_membre=?");
            $reqDate -> execute(array($newDate, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newPays']) AND !empty($_POST['newPays']) AND $_POST['newPays'] != $donnees['pays']){
            $newPays = htmlspecialchars($_POST['newPays']);
            $reqPays = $bdd -> prepare("UPDATE membres SET pays=? WHERE id_membre=?");
            $reqPays -> execute(array($newPays, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newSexe']) AND !empty($_POST['newSexe']) AND $_POST['newSexe'] != $donnees['sexe']){
            $newSexe = htmlspecialchars($_POST['newSexe']);
            $reqSexe = $bdd -> prepare("UPDATE membres SET sexe=? WHERE id_membre=?");
            $reqSexe -> execute(array($newSexe, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newProfession']) AND !empty($_POST['newProfession']) AND $_POST['newProfession'] != $donnees['profession']){
            $newProfession = htmlspecialchars($_POST['newProfession']);
            $reqProfession = $bdd -> prepare("UPDATE membres SET profession=? WHERE id_membre=?");
            $reqProfession -> execute(array($newProfession, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newEmail']) AND !empty($_POST['newEmail']) AND $_POST['newEmail'] != $donnees['email']){
            $newEmail = htmlspecialchars($_POST['newEmail']);
            $reqEmail = $bdd -> prepare("UPDATE membres SET email=? WHERE id_membre=?");
            $reqEmail -> execute(array($newEmail, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
    }
    if(isset($_POST['newPassword']) AND !empty($_POST['newPassword']) AND isset($_POST['newConfpassword']) AND !empty($_POST['newConfpassword'])){
            $newPassword = sha1($_POST['newPassword']);
            $newConfpassword = sha1($_POST['newConfpassword']);
            if($newPassword == $newConfpassword){
                $reqPassword = $bdd -> prepare("UPDATE membres SET mot_passe=? WHERE id_membre=?");
                $reqPassword -> execute(array($newPassword, $_SESSION['id']));
                header('Location: profil.php?id='.$_SESSION['id']);
            }else{
                $erreur = 'Les mots de passe ne sont pas identiques !';
            }
    }

    if(isset($_FILES['newAvatar']) AND !empty($_FILES['newAvatar']['name'])){
        $tailleMax = 2097152;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['newAvatar']['size'] <= $tailleMax){
            $extensionsUpload = strtolower(substr(strrchr($_FILES['newAvatar']['name'], '.'), 1));
            if(in_array($extensionsUpload, $extensionsValides)){
                $chemin = '../img/membres/avatars/'.$_SESSION['id'].'.'.$extensionsUpload;
                $resultat = move_uploaded_file($_FILES['newAvatar']['tmp_name'], $chemin);
                if($resultat){
                    $reqAvatar = $bdd -> prepare("UPDATE membres SET avatar = :avatar WHERE id_membre = :id_membre");
                    $reqAvatar -> execute(array(
                        'avatar'=> $_SESSION['id'].'.'.$extensionsUpload,
                        'id_membre' => $_SESSION['id']
                    ));
                    header('Location: profil.php?id='.$_SESSION['id']);
                }else{
                    $erreur = 'Erreur lors de l\'importation de la photo !';
                }
            }else{
                $erreur = 'Votre photo de profil doit être au format (jpg, jpeg, gif ou png)';
            }
        }else{
            $erreur = 'Votre photo de profil ne doit pas dépasser 2MO';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mon profil</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="../css/style.css"/>
        <link rel="icon" type="image/png" href="../img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/ionicons.css">
    </head>
    <body>
        <?php include("header.php"); ?>
        <div class="connexion_block">
            <form class="identification" method="POST" action="#" enctype="multipart/form-data">
                <h1 class="titre">Edition de mon profil</h1>
                <label for="newNom">Nom:</label>
                <input type="text" id="newNom" name="newNom" placeholder="Entrer votre nom" value="<?php echo $donnees['nom_membre']; ?>"/>

                <label for="newPrenom">Prénom:</label>
                <input type="text" id="newPrenom" name="newPrenom" placeholder="Entrer votre prénom" value="<?php echo $donnees['prenom_membre']; ?>"/>

                <label for="newDate">Date de naissance : </label>
                <input type="date" name="newDate" id="newDate" value="<?php echo $donnees['date_naissance']; ?>"/>

                <label for="pays">Pays:</label><br/>
                <select name="newPays" id="pays">
                    <option value="Guinee" selected="selected">Guinée</option>
                    <option value="Afghanistan">Afghanistan </option>
                    <option value="Afrique_Centrale">Afrique Centrale </option>
                    <option value="Afrique_du_Sud">Afrique du Sud </option>
                    <option value="Albanie">Albanie </option>
                    <option value="Algerie">Algérie </option>
                    <option value="Allemagne">Allemagne </option>
                    <option value="Andorre">Andorre </option>
                    <option value="Angola">Angola </option>
                    <option value="Anguilla">Anguilla </option>
                    <option value="Arabie_Saoudite">Arabie Saoudite </option>
                    <option value="Argentine">Argentine </option>
                    <option value="Armenie">Armenie </option>
                    <option value="Australie">Australie </option>
                    <option value="Autriche">Autriche </option>
                    <option value="Azerbaidjan">Azerbaidjan </option>
                    <option value="Bahamas">Bahamas </option>
                    <option value="Bangladesh">Bangladesh </option>
                    <option value="Barbade">Barbade </option>
                    <option value="Bahrein">Bahrein </option>
                    <option value="Belgique">Belgique </option>
                    <option value="Belize">Belize </option>
                    <option value="Benin">Bénin </option>
                    <option value="Bermudes">Bermudes </option>
                    <option value="Bielorussie">Bielorussie </option>
                    <option value="Bolivie">Bolivie </option>
                    <option value="Botswana">Botswana </option>
                    <option value="Bhoutan">Bhoutan </option>
                    <option value="Boznie_Herzegovine">Boznie Herzégovine </option>
                    <option value="Bresil">Brésil </option>
                    <option value="Brunei">Brunei </option>
                    <option value="Bulgarie">Bulgarie </option>
                    <option value="Burkina_Faso">Burkina Faso </option>
                    <option value="Burundi">Burundi </option>
                    <option value="Caiman">Caiman </option>
                    <option value="Cambodge">Cambodge </option>
                    <option value="Cameroun">Cameroun </option>
                    <option value="Canada">Canada </option>
                    <option value="Canaries">Canaries </option>
                    <option value="Cap_vert">Cap Vert </option>
                    <option value="Chili">Chili </option>
                    <option value="Chine">Chine </option>
                    <option value="Chypre">Chypre </option>
                    <option value="Colombie">Colombie </option>
                    <option value="Comores">Colombie </option>
                    <option value="Congo">Congo </option>
                    <option value="Congo_democratique">Congo Démocratique </option>
                    <option value="Cook">Cook </option>
                    <option value="Coree_du_Nord">Corée du Nord </option>
                    <option value="Coree_du_Sud">Corée du Sud </option>
                    <option value="Costa_Rica">Costa Rica </option>
                    <option value="Cote_d_Ivoire">Côte d’Ivoire </option>
                    <option value="Croatie">Croatie </option>
                    <option value="Cuba">Cuba </option>
                    <option value="Danemark">Danemark </option>
                    <option value="Djibouti">Djibouti </option>
                    <option value="Dominique">Dominique </option>
                    <option value="Egypte">Egypte </option>
                    <option value="Emirats_Arabes_Unis">Emirats Arabes Unis </option>
                    <option value="Equateur">Equateur </option>
                    <option value="Erythree">Erythrée </option>
                    <option value="Espagne">Espagne </option>
                    <option value="Estonie">Estonie </option>
                    <option value="Etats_Unis">Etats-Unis </option>
                    <option value="Ethiopie">Ethiopie </option>
                    <option value="Falkland">Falkland </option>
                    <option value="Feroe">Feroe </option>
                    <option value="Fidji">Fidji </option>
                    <option value="Finlande">Finlande </option>
                    <option value="France">France </option>
                    <option value="Gabon">Gabon </option>
                    <option value="Gambie">Gambie </option>
                    <option value="Georgie">Géorgie </option>
                    <option value="Ghana">Ghana </option>
                    <option value="Gibraltar">Gibraltar </option>
                    <option value="Grece">Grece </option>
                    <option value="Grenade">Grenade </option>
                    <option value="Groenland">Groënland </option>
                    <option value="Guadeloupe">Guadeloupe </option>
                    <option value="Guam">Guam </option>
                    <option value="Guatemala">Guatémala</option>
                    <option value="Guernesey">Guernesey </option>
                    <option value="Guinee">Guinée </option>
                    <option value="Guinee_Bissau">Guinée Bissau </option>
                    <option value="Guinee equatoriale">Guinée Equatoriale </option>
                    <option value="Guyana">Guyana </option>
                    <option value="Guyane_Francaise ">Guyane Francaise </option>
                    <option value="Haiti">Haiti </option>
                    <option value="Hawaii">Hawaii </option>
                    <option value="Honduras">Honduras </option>
                    <option value="Hong_Kong">Hong Kong </option>
                    <option value="Hongrie">Hongrie </option>
                    <option value="Inde">Inde </option>
                    <option value="Indonesie">Indonésie </option>
                    <option value="Iran">Iran </option>
                    <option value="Iraq">Iraq </option>
                    <option value="Irlande">Irlande </option>
                    <option value="Islande">Islande </option>
                    <option value="Israel">Israel </option>
                    <option value="Italie">italie </option>
                    <option value="Jamaique">Jamaïque </option>
                    <option value="Jan Mayen">Jan Mayen </option>
                    <option value="Japon">Japon </option>
                    <option value="Jersey">Jersey </option>
                    <option value="Jordanie">Jordanie </option>
                    <option value="Kazakhstan">Kazakhstan </option>
                    <option value="Kenya">Kenya </option>
                    <option value="Kirghizstan">Kirghizistan </option>
                    <option value="Kiribati">Kiribati </option>
                    <option value="Koweit">Koweït </option>
                    <option value="Laos">Laos </option>
                    <option value="Lesotho">Lesotho </option>
                    <option value="Lettonie">Lettonie </option>
                    <option value="Liban">Liban </option>
                    <option value="Liberia">Liberia </option>
                    <option value="Liechtenstein">Liechtenstein </option>
                    <option value="Lituanie">Lituanie </option>
                    <option value="Luxembourg">Luxembourg </option>
                    <option value="Lybie">Lybie </option>
                    <option value="Macao">Macao </option>
                    <option value="Macedoine">Macédoine </option>
                    <option value="Madagascar">Madagascar </option>
                    <option value="Madère">Madère </option>
                    <option value="Malaisie">Malaisie </option>
                    <option value="Malawi">Malawi </option>
                    <option value="Maldives">Maldives </option>
                    <option value="Mali">Mali </option>
                    <option value="Malte">Malte </option>
                    <option value="Man">Man </option>
                    <option value="Mariannes du Nord">Mariannes du Nord </option>
                    <option value="Maroc">Maroc </option>
                    <option value="Marshall">Marshall </option>
                    <option value="Martinique">Martinique </option>
                    <option value="Maurice">Maurice </option>
                    <option value="Mauritanie">Mauritanie </option>
                    <option value="Mayotte">Mayotte </option>
                    <option value="Mexique">Mexique </option>
                    <option value="Micronesie">Micronesie </option>
                    <option value="Midway">Midway </option>
                    <option value="Moldavie">Moldavie </option>
                    <option value="Monaco">Monaco </option>
                    <option value="Mongolie">Mongolie </option>
                    <option value="Montserrat">Montserrat </option>
                    <option value="Mozambique">Mozambique </option>
                    <option value="Namibie">Namibie </option>
                    <option value="Nauru">Nauru </option>
                    <option value="Nepal">Nepal </option>
                    <option value="Nicaragua">Nicaragua </option>
                    <option value="Niger">Niger </option>
                    <option value="Nigeria">Nigéria </option>
                    <option value="Niue">Niue </option>
                    <option value="Norfolk">Norfolk </option>
                    <option value="Norvege">Norvège </option>
                    <option value="Nouvelle_Caledonie">Nouvelle Calédonie </option>
                    <option value="Nouvelle_Zelande">Nouvelle Zélande </option>
                    <option value="Oman">Oman </option>
                    <option value="Ouganda">Ouganda </option>
                    <option value="Ouzbekistan">Ouzbékistan </option>
                    <option value="Pakistan">Pakistan </option>
                    <option value="Palau">Palau </option>
                    <option value="Palestine">Palestine </option>
                    <option value="Panama">Panama </option>
                    <option value="Papouasie_Nouvelle_Guinee">Papouasie Nouvelle Guinée</option>
                    <option value="Paraguay">Paraguay </option>
                    <option value="Pays_Bas">Pays Bas </option>
                    <option value="Perou">Perou </option>
                    <option value="Philippines">Philippines </option>
                    <option value="Pologne">Pologne </option>
                    <option value="Polynesie">Polynesie </option>
                    <option value="Porto_Rico">Porto Rico </option>
                    <option value="Portugal">Portugal </option>
                    <option value="Qatar">Qatar </option>
                    <option value="Republique_Dominicaine">Republique Dominicaine </option>
                    <option value="Republique_Tcheque">Republique Tcheque </option>
                    <option value="Reunion">Reunion </option>
                    <option value="Roumanie">Roumanie </option>
                    <option value="Royaume_Uni">Royaume Uni </option>
                    <option value="Russie">Russie </option>
                    <option value="Rwanda">Rwanda </option>
                    <option value="Sahara Occidental">Sahara Occidental </option>
                    <option value="Sainte_Lucie">Sainte-Lucie </option>
                    <option value="Saint_Marin">Saint-Marin </option>
                    <option value="Salomon">Salomon </option>
                    <option value="Salvador">Salvador </option>
                    <option value="Samoa_Occidentales">Samoa Occidentales</option>
                    <option value="Samoa_Americaine">Samoa Americaine </option>
                    <option value="Sao_Tome_et_Principe">Sao Tome et Principe </option>
                    <option value="Senegal">Sénégal </option>
                    <option value="Seychelles">Seychelles </option>
                    <option value="Sierra Leone">Sierra Leone </option>
                    <option value="Singapour">Singapour </option>
                    <option value="Slovaquie">Slovaquie </option>
                    <option value="Slovenie">Slovénie</option>
                    <option value="Somalie">Somalie </option>
                    <option value="Soudan">Soudan </option>
                    <option value="Sri_Lanka">Sri Lanka </option>
                    <option value="Suede">Suede </option>
                    <option value="Suisse">Suisse </option>
                    <option value="Surinam">Surinam </option>
                    <option value="Swaziland">Swaziland </option>
                    <option value="Syrie">Syrie </option>
                    <option value="Tadjikistan">Tadjikistan </option>
                    <option value="Taiwan">Taiwan </option>
                    <option value="Tonga">Tonga </option>
                    <option value="Tanzanie">Tanzanie </option>
                    <option value="Tchad">Tchad </option>
                    <option value="Thailande">Thailande </option>
                    <option value="Tibet">Tibet </option>
                    <option value="Timor_Oriental">Timor Oriental </option>
                    <option value="Togo">Togo </option>
                    <option value="Trinite_et_Tobago">Trinite et Tobago </option>
                    <option value="Tristan da cunha">Tristan de cuncha </option>
                    <option value="Tunisie">Tunisie </option>
                    <option value="Turkmenistan">Turmenistan </option>
                    <option value="Turquie">Turquie </option>
                    <option value="Ukraine">Ukraine </option>
                    <option value="Uruguay">Uruguay </option>
                    <option value="Vanuatu">Vanuatu </option>
                    <option value="Vatican">Vatican </option>
                    <option value="Venezuela">Vénézuela </option>
                    <option value="Vierges_Americaines">Vierges Américaines </option>
                    <option value="Vierges_Britanniques">Vierges Britanniques </option>
                    <option value="Vietnam">Vietnam </option>
                    <option value="Wake">Wake </option>
                    <option value="Wallis et Futuma">Wallis et Futuma </option>
                    <option value="Yemen">Yemen </option>
                    <option value="Yougoslavie">Yougoslavie </option>
                    <option value="Zambie">Zambie </option>
                    <option value="Zimbabwe">Zimbabwe </option>
                </select><br/>

                <label>Sexe:</label><br/>
                <input type="radio" name="newSexe" value="Homme" class="radiobutton"/><span>Homme</span>
                <input type="radio" name="newSexe" value="Femme" class="radiobutton"/><span>Femme</span><br/>

                <label>Profession:</label><br/>
                <input type="radio" name="newProfession" value="Professeur" class="radiobutton"/><span>Professeur</span>
                <input type="radio" name="newProfession" value="Eleve" class="radiobutton"/><span>Elève</span>
                <input type="radio" name="newProfession" value="Autre" class="radiobutton"/><span>Autre</span><br/>

                <label for="newEmail">Email:</label>
                <input type="email" id="newEmail" name="newEmail" placeholder="Entrer votre Email" value="<?php echo $donnees['email']; ?>"/>
                
                <label for="newPassword">Mot de passe</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Entrer le mot de passe"/>

                <label for="newConfpassword">Confirmation du mot de passe:</label>
                <input type="password" id="newConfpassword" name="newConfpassword" placeholder="Entrer le mot de passe"/>
                
                <label for="newAvatar">Avatar: </label>
                <input type="file" id="newAvatar" name="newAvatar"/>

                <input class="button" type="submit" name="valider" value="Enregistrer"/>
                <input class="button" type="reset" name="annuler" value="Annuler"/>
            </form>
            <?php 
                if(isset($erreur)){
                    echo $erreur;
                }
            ?>
        </div>
        <?php include("footer.php"); ?>
    </body>
</html>
<?php
}else{
    header("Location : connexion.php");
}
?>