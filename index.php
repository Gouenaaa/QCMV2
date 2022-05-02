<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <!-- On a pas réussi à set la hauteur et la largeur des divs avec tailwind donc elles sont dans une feuille css -->
    <link rel="stylesheet" href="./CSS/index.css">
    <script src="./JS/JQuery.js"></script>
    <script src="./JS/jquery.color.js"></script>
    <script src="./JS/fonctions.js"></script>
    <title>QR</title>
    <?php
        session_start();
        include './PHP/cnx.php';
        unset($_SESSION["idU"]);
        $msgalert = "";
        if(isset($_POST['btnConnexion'])){
            $_SESSION['loginU'] = $_POST['loginU'];

            //Requete de récupération de l'id du login
            $login = $cnx->prepare("SELECT idEtudiant
            FROM etudiants
            WHERE login = :login");
            $login->bindValue(":login", $_SESSION['loginU']);
            $login->execute();
            $login = $login->fetch(PDO::FETCH_ASSOC);
            $_SESSION["idU"] = $login["idEtudiant"];

            //Si l'id est null le login n'existe pas
            if(!$login){
                $tf = "false";
                $msgalert = "Identifiant inconnu.";
            }
            else{
                //Requete de récupération du mdp du login
                $password = $cnx->prepare("SELECT motDePasse
                FROM etudiants
                WHERE login = :login");
                $password->bindValue(":login", $_SESSION['loginU']);
                $password->execute();
                $password = $password->fetch(PDO::FETCH_ASSOC);

                //Si le mdp est le bon on envoie vers la page d'accueil
                if($password["motDePasse"] == $_POST["passwordU"]){
                    header("Location: ./PHP/accueil.php");
                }
                else{
                    $tf = "false";
                    $msgalert = "Mot de passe incorrecte.";
                }
            }
        }
        else{
            $tf = "true";
        }
    ?>
    <script>
        $(
            function(){
                if(<?php echo $tf; ?>){
                    $("#divLogo").mouseenter(AfficherLogo);
                    $("#divLogo").mouseleave(AfficherNom);
                    $("#divLogo").click(Son);
                }
                else{
                    divConnexion();
                    alert("<?php echo $msgalert; ?>");
                }
                
            }
        );
    </script>
</head>
<body class="flex h-screen justify-center items-center bg-green-100">
    <div id="divLogo" class="flex justify-center items-center">
        <img id="imgNom" src="./MEDIAS/nom.png" alt="">
    </div>
</body>
</html>