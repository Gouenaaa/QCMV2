<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/creerQuestion.css">
    <script src="../JS/JQuery.js"></script>
    <script src="../JS/fonctions.js"></script>
    <script src="../JS/jquery.color.js"></script>
    <script>
        $(
            function(){
                $("#btnAjouterReponse").click(ajouterReponse);
                $(document).on("click", "#btnSupprimer", function(){
                    r = r-1;
                    $(this).parent().remove();
                    
                    if(r!=2){
                        $("#divReponse"+r+"").append("<input type='button' value='Supprimer Reponse' id='btnSupprimer'>");
                    }
                    $("#vr").remove();
                    $("#frmQuestion").append("<input id='vr' type='hidden' name='vr' value='"+r+"'>");
                });
            }
        );
    </script>
    <title>QR</title>
</head>
<body>
    <?php
    session_start();
    include "./cnx.php";

    if(isset($_POST["reponse1"])){
        $nbReponses = $_POST["vr"];
        $listeReponses = array();
        $nbBonneReponses = 0;
        for($i = 1; $i <= $nbReponses; $i++){
            $listeReponses["reponse"][strval($i)] = $_POST["reponse".strval($i)];
            if(isset($_POST["bonneReponse".strval($i)])){
                $nbBonneReponses++;
                $listeReponses["bonneReponse"][strval($i)] = 1;
            }
            else{
                $listeReponses["bonneReponse"][strval($i)] = 0;
            }
        }
        if($nbBonneReponses == 0){
            echo "<script>alert('Veuillez sélectionner au moins une bonne réponse');</script>";
        }
        else{
            if($nbBonneReponses == 1){
                $type = 1;
            }
            else{
                $type = 2;
            }
            $chemin = $_POST["lien"];
            $libelle = $_POST["libelle"];
    
            //RQT créer question
            $question = $cnx->prepare("INSERT INTO question (libelleQuestion, type, nbReponse, nbBonneReponse, cheminImgQuestion)
            VALUES (:libelleQuestion, :type, :nbReponse, :nbBonneReponse, :cheminImgQuestion)");
            $question->bindValue(":libelleQuestion", $libelle, PDO::PARAM_STR);
            $question->bindValue(":type", $type, PDO::PARAM_INT);
            $question->bindValue(":nbReponse", $nbReponses, PDO::PARAM_INT);
            $question->bindValue(":nbBonneReponse", $nbBonneReponses, PDO::PARAM_INT);
            $question->bindValue(":cheminImgQuestion", $chemin, PDO::PARAM_STR);
            $question->execute();

            //RQT créer réponses
            for($i = 1; $i <= $nbReponses; $i++){
                $reponses = $cnx->prepare("INSERT INTO reponse (valeur)
                                            VALUES ( :valeurs ) ");
                $reponses->bindValue(":valeurs", $listeReponses["reponse"][strval($i)], PDO::PARAM_STR);
                $reponses->execute();
            }

            //RQT id question
            $idQ = $cnx->prepare("SELECT MAX(idQuestion)
            FROM question");
            $idQ->execute();
            $idQ = $idQ->fetch(PDO::FETCH_NUM);

            //RQT id réponses
            $idRs = array();
            for($i = $nbReponses-1; $i >= 0; $i--){
                $idR = $cnx->prepare("SELECT MAX(idReponse-".$i.")
                                        FROM reponse");
                $idR->execute();
                $idR = $idR->fetch(PDO::FETCH_NUM);
                $idRs[strval(($nbReponses-1-$i))] = $idR[0];
            }

            //RQT lien question-réponses
            for($i = 1; $i <= $nbReponses; $i++){
                $cbx = "bonneReponse".$i;
                if(isset($_POST[$cbx])){
                    $bonne = 1;
                }
                else{
                    $bonne = 0;
                }

                $qr = $cnx->prepare("INSERT INTO questionreponse (idQuestion, idReponse, ordre, bonne)
                                        VALUES (:idQuestion, :idReponse, :ordre, :bonne)");
                $qr->bindValue(":idQuestion", $idQ[0], PDO::PARAM_INT);
                $qr->bindValue(":idReponse", $idRs[strval($i-1)], PDO::PARAM_INT);
                $qr->bindValue(":ordre", $i, PDO::PARAM_INT);
                $qr->bindValue(":bonne", $bonne, PDO::PARAM_INT);
                $qr->execute();
            }
            



            //On valide
            header("Location: ./accueil.php");

            
        }
        
    }




    ?>
    <div id="head">
        <div id="divLogo">
            <a href="./accueil.php"><img id="imgLogo" src="../MEDIAS/logoContour.png"></a>
        </div>
        <div id="divProfil">
            <a href="./profil.php?idU=".<?php $_SESSION["idU"]?>><?php echo $_SESSION["loginU"]; ?></a>
            
        </div>
    </div>

    <div id="divContainer">
        <div id="divCreerQuestion">
            <form id="frmQuestion" action="./creerQuestion.php" method="post">
                <input id='vr' type='hidden' name='vr' value='2'>
                <div id="divIntitule">
                    <input required type="text" name="libelle" id="txtIntitule" placeholder="Intitulé de la question">
                </div>
                <div id="divReponses">
                    <div class='divReponse' id='divReponse1'>
                        <input required type='text' name='reponse1' id='txtReponse1' placeholder='Réponse 1'>
                        <input type='checkbox' name='bonneReponse1' class='cbxReponse'>
                        <label for='cbxReponse'>Bonne Réponse</label>
                    </div>
                    <div class='divReponse' id='divReponse2'>
                        <input required type='text' name='reponse2' id='txtReponse2' placeholder='Réponse 2'>
                        <input type='checkbox' name='bonneReponse2' class='cbxReponse'>
                        <label for='cbxReponse'>Bonne Réponse</label>
                    </div>
                </div>
                <input id="btnAjouterReponse" type="button" value="Ajouter une réponse">
                <input type="text" name="lien" id="txtLien" placeholder="Lien vers une image">
                <div id="divConfirmation">
                    <input type="submit" value="Confirmer">
                    <a id="btnAnnuler" href="./accueil.php"><input type="button" value="Annuler"></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>